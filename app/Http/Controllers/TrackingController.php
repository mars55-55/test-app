<?php
namespace App\Http\Controllers;

use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class TrackingController extends Controller
{
    // Lista blanca de productos (usar exactamente los nombres solicitados)
    protected array $allowedProducts = [
        'papa criolla',
        'papa negra*',
        'sabanera',
        'zanahoria',
        'cebolla larga',
        'habas',
        'Arveja verde en vaina',
        'coliflor (repollo)',
        'brócoli',
        'lechuga',
        'espinaca',
        'apio',
        'ajo',
        'remolacha',
        'navos',
        'fresas',
        'uchua',
        'Mora de Castilla',
        'lulo',
        'curuba',
        'durazno',
    ];

    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = optional($user->role)->name === 'admin';

        // Query base
        $trackingsQuery = Tracking::query()->orderByDesc('created_at');

        // Aplicar filtro de productos permitidos
        $trackingsQuery->whereIn('producto', $this->allowedProducts);

        // Si es admin sólo ver registros que tengan fecha_siembra y fecha_cosecha
        if ($isAdmin) {
            $trackingsQuery->whereNotNull('fecha_siembra')
                           ->whereNotNull('fecha_cosecha');
        } else {
            // si no es admin solo ver los del agricultor
            $trackingsQuery->where('agricultor_id', $user->id);
        }

        // paginar (admin y agricultor usan paginación)
        $perPage = 25;
        $trackings = $trackingsQuery->paginate($perPage)->withQueryString();

        // Región objetivo: Bogotá / Sabana de Occidente (filtrado para estadísticas/regionales)
        $regionQuery = Tracking::query()
            ->whereIn('producto', $this->allowedProducts)
            ->where(function($q){
                $q->where('departamento','LIKE','%BOGOT%')
                  ->orWhere('ciudad','LIKE','%BOGOT%')
                  ->orWhere('departamento','LIKE','%CUNDINAMARCA%')
                  ->orWhere('ciudad','LIKE','%SABANA%');
            });

        // Stats por producto: cantidad sembrada y precio promedio (para la región)
        $productosStats = (clone $regionQuery)
            ->whereNotNull('producto')
            ->selectRaw('producto, COUNT(*) as sembrados, AVG(estimacion_ganancia) as precio_promedio')
            ->groupBy('producto')
            ->orderByDesc('sembrados')
            ->get();

        // Intentar obtener duraciones desde fuente remota (configurable)
        $remoteDuraciones = collect();
        $remoteUrl = env('REMOTE_PRODUCT_DURATIONS_URL', null);
        if ($remoteUrl) {
            try {
                $resp = Http::timeout(5)->get($remoteUrl);
                if ($resp->ok()) {
                    $json = $resp->json();
                    $remoteDuraciones = collect($json);
                    // normalizar formatos: support array of {product,duration_days} or object product->days
                    if ($remoteDuraciones->isNotEmpty() && $remoteDuraciones->keys()->first() === 0 && isset($remoteDuraciones[0]['product'])) {
                        $remoteDuraciones = $remoteDuraciones->pluck('duration_days','product')->map(function($v){ return (int) $v; });
                    } else {
                        $remoteDuraciones = $remoteDuraciones->map(function($v){ return (int) $v; });
                    }
                }
            } catch (\Exception $e) {
                // silenciar error; seguiremos con BD/default
                $remoteDuraciones = collect();
            }
        }

        // Duración media por producto desde BD (enteros), limitado a productos permitidos y región
        $dbDuraciones = (clone $regionQuery)
            ->whereNotNull('fecha_siembra')
            ->whereNotNull('fecha_cosecha')
            ->selectRaw("producto, AVG(DATEDIFF(fecha_cosecha, fecha_siembra)) as duracion_media")
            ->groupBy('producto')
            ->pluck('duracion_media','producto')
            ->map(function($v){ return (int) round($v); });

        // Merge remote > db (remote tiene prioridad)
        $duraciones = $remoteDuraciones->merge($dbDuraciones);

        // Selector de producto para serie temporal (si se pasa por query) - validar que esté dentro de la whitelist
        $selectedProduct = $request->query('producto', $productosStats->first()->producto ?? null);
        // Normalizar a minúsculas para comparación segura
        $selectedProductNorm = $selectedProduct ? mb_strtolower($selectedProduct) : null;
        $allowedNorm = array_map('mb_strtolower', $this->allowedProducts);
        if ($selectedProduct && !in_array($selectedProductNorm, $allowedNorm)) {
            $selectedProduct = null;
            $selectedProductNorm = null;
        }
        $start = $request->query('start'); // formato YYYY-MM
        $end = $request->query('end');

        // Serie temporal mensual para el producto seleccionado (precio promedio por mes)
        $serie = collect();
        if ($selectedProduct) {
            // Consultas case-insensitive: comparar LOWER(producto) con valor normalizado
            $serieQuery = Tracking::query()
                ->whereRaw('LOWER(producto) = ?', [$selectedProductNorm])
                ->whereIn(DB::raw('LOWER(producto)'), $allowedNorm);

            // Primero intentamos agrupar por fecha_precio cuando exista
            $serieQueryByPrecio = (clone $serieQuery)->whereNotNull('fecha_precio');

            // aplicar filtro de región para series
            $serieQuery->where(function($q){
                $q->where('departamento','LIKE','%BOGOT%')
                  ->orWhere('ciudad','LIKE','%BOGOT%')
                  ->orWhere('departamento','LIKE','%CUNDINAMARCA%')
                  ->orWhere('ciudad','LIKE','%SABANA%');
            });

            if ($start) {
                $serieQueryByPrecio->where('fecha_precio', '>=', $start . '-01');
            }
            if ($end) {
                $lastDay = Carbon::parse($end . '-01')->endOfMonth()->toDateString();
                $serieQueryByPrecio->where('fecha_precio', '<=', $lastDay);
            }

            $serie = $serieQueryByPrecio
                ->selectRaw("DATE_FORMAT(fecha_precio, '%Y-%m') as ym, AVG(estimacion_ganancia) as avg_price")
                ->groupBy('ym')
                ->orderBy('ym')
                ->get();

            // Si no hay datos por fecha_precio, hacer fallback agrupando por created_at (mes de registro)
            if ($serie->isEmpty()) {
                $serieQueryByCreated = (clone $serieQuery);
                if ($start) {
                    $serieQueryByCreated->where('created_at', '>=', $start . '-01');
                }
                if ($end) {
                    $lastDay = Carbon::parse($end . '-01')->endOfMonth()->toDateString();
                    $serieQueryByCreated->where('created_at', '<=', $lastDay . ' 23:59:59');
                }
                $serie = $serieQueryByCreated
                    ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, AVG(estimacion_ganancia) as avg_price")
                    ->groupBy('ym')
                    ->orderBy('ym')
                    ->get();
            }
        }

        // Recomendación simple para agricultor: top producto para el mes actual en la región (solo productos permitidos)
        $recomendado = null;
        if (optional($user->role)->name === 'agricultor') {
            $mes = Carbon::now()->month;
            $rec = (clone $regionQuery)
                ->whereMonth('fecha_siembra', $mes)
                ->whereIn('producto', $this->allowedProducts)
                ->selectRaw('producto, COUNT(*) as frecuencia, AVG(estimacion_ganancia) as ganancia_promedio')
                ->groupBy('producto')
                ->orderByDesc('frecuencia')
                ->orderByDesc('ganancia_promedio')
                ->first();
            $recomendado = $rec ? $rec->producto : null;
        }

        return view('tracking.index', compact(
            'trackings',
            'productosStats',
            'serie',
            'selectedProduct',
            'duraciones',
            'recomendado'
        ));
    }

    public function create() { return view('tracking.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'producto' => ['required','string'],
            'fecha_siembra' => 'nullable|date',
            'fecha_cosecha' => 'nullable|date',
            'estimacion_ganancia' => 'nullable|numeric',
        ]);

        if (!in_array($request->producto, $this->allowedProducts)) {
            return back()->withErrors(['producto' => 'Producto no permitido.']);
        }

        Tracking::create([
            'agricultor_id' => Auth::id(),
            'producto' => $request->producto,
            'fecha_siembra' => $request->fecha_siembra,
            'fecha_cosecha' => $request->fecha_cosecha,
            'estimacion_ganancia' => $request->estimacion_ganancia,
            'notas' => $request->notas ?? null,
        ]);
        return redirect()->route('tracking.index');
    }

    public function sembrarForm()
    {
        return view('tracking.sembrar');
    }

    public function recomendar(Request $request)
    {
        $request->validate(['fecha_inicio' => 'required|date']);
        $fecha = Carbon::parse($request->fecha_inicio);
        $mes = $fecha->month;

        // Filtrar registros históricos para Bogotá / Sabana (departamento o ciudad) y whitelist de productos
        $query = Tracking::query()
            ->whereIn('producto', $this->allowedProducts)
            ->where(function($q){
                $q->where('departamento', 'LIKE', '%BOGOT%')
                  ->orWhere('ciudad', 'LIKE', '%BOGOT%')
                  ->orWhere('departamento', 'LIKE', '%CUNDINAMARCA%');
            });

        // calcular frecuencia, ganancia media y duración media por producto en ese mes (Bogotá)
        $recomendaciones = (clone $query)
            ->whereMonth('fecha_siembra', $mes)
            ->selectRaw('producto, COUNT(*) as frecuencia, AVG(estimacion_ganancia) as ganancia_promedio, AVG(DATEDIFF(fecha_cosecha, fecha_siembra)) as duracion_media')
            ->groupBy('producto')
            ->orderByDesc('frecuencia')
            ->orderByDesc('ganancia_promedio')
            ->get()
            ->take(8);

        if ($recomendaciones->isEmpty()) {
            // si no hay datos por mes, usar top por ganancia promedio en Bogotá (solo productos permitidos)
            $recomendaciones = (clone $query)
                ->selectRaw('producto, COUNT(*) as frecuencia, AVG(estimacion_ganancia) as ganancia_promedio, AVG(DATEDIFF(fecha_cosecha, fecha_siembra)) as duracion_media')
                ->groupBy('producto')
                ->orderByDesc('ganancia_promedio')
                ->get()
                ->take(8);
        }

        // Obtener clima histórico/estimado para la Sabana de Occidente (coordenadas aproximadas)
        $lat = 4.7639;
        $lon = -74.3459;
        $start = $fecha->copy()->startOfMonth()->toDateString();
        $end = $fecha->copy()->endOfMonth()->toDateString();

        $clima = null;
        try {
            $resp = Http::timeout(6)->get('https://archive-api.open-meteo.com/v1/era5', [
                'latitude' => $lat,
                'longitude' => $lon,
                'start_date' => $start,
                'end_date' => $end,
                'daily' => 'temperature_2m_mean,precipitation_sum',
                'timezone' => 'America/Bogota',
            ]);

            if ($resp->ok()) {
                $json = $resp->json();
                $temps = $json['daily']['temperature_2m_mean'] ?? null;
                $precip = $json['daily']['precipitation_sum'] ?? null;
                if ($temps && $precip) {
                    $avgTemp = array_sum($temps) / count($temps);
                    $totalPrecip = array_sum($precip);
                    $clima = [
                        'avg_temp' => round($avgTemp,1),
                        'total_precip' => round($totalPrecip,1),
                    ];
                }
            }
        } catch (\Exception $e) {
            $clima = null;
        }

        // Calcular un puntaje simple por producto (frecuencia*0.5 + ganancia_norm*0.4 + duracion_factor*0.1)
        $maxGan = $recomendaciones->max('ganancia_promedio') ?: 1;
        foreach ($recomendaciones as $r) {
            $ganNorm = $r->ganancia_promedio / $maxGan;
            $duracion = $r->duracion_media ? floatval($r->duracion_media) : 90;
            $duracion = (int) round($duracion);
            $durFactor = max(0, 1 - abs($duracion - 120)/180);
            $r->score = ($r->frecuencia * 0.5) + ($ganNorm * 0.4) + ($durFactor * 0.1);
            $r->estimacion_dias = (int) ($duracion ? $duracion : 120);
            $r->fecha_cosecha_estimada = $fecha->copy()->addDays($r->estimacion_dias)->toDateString();
        }

        $recomendaciones = $recomendaciones->sortByDesc('score')->values();

        return view('tracking.sembrar', [
            'fecha_inicio' => $fecha->toDateString(),
            'recomendaciones' => $recomendaciones,
            'clima' => $clima,
        ]);
    }

    public function confirmarSiembra(Request $request)
    {
        $request->validate([
            'producto' => ['required','string'],
            'fecha_inicio' => 'required|date',
            'estimacion_dias' => 'required|integer',
            'estimacion_ganancia' => 'nullable|numeric',
        ]);

        // Validación case-insensitive contra la whitelist
        $productoInput = trim((string) $request->producto);
        $productoNorm = mb_strtolower($productoInput);
        $allowedNorm = array_map('mb_strtolower', $this->allowedProducts);
        if (!in_array($productoNorm, $allowedNorm)) {
            return back()->withErrors(['producto' => 'Producto no permitido.'])->withInput();
        }

        $fecha_siembra = Carbon::parse($request->fecha_inicio);
        $dias = (int) $request->estimacion_dias;
        $fecha_cosecha = $fecha_siembra->copy()->addDays($dias);

        // Guardar el producto tal como lo envía el usuario (pero se podría normalizar si se desea)
        Tracking::create([
            'agricultor_id' => Auth::id(),
            'producto' => $productoInput,
            'fecha_siembra' => $fecha_siembra->toDateString(),
            'fecha_cosecha' => $fecha_cosecha->toDateString(),
            'estimacion_ganancia' => $request->estimacion_ganancia ? (float) $request->estimacion_ganancia : 0,
            'notas' => 'Siembra confirmada por agricultor (recomendación automatizada)',
            'fuente' => 'Recomendador-Sabana',
            'departamento' => 'CUNDINAMARCA/BOGOTÁ',
            'ciudad' => 'Sabana de Occidente',
        ]);

        return redirect()->route('tracking.index')->with('success','Siembra confirmada y registrada en tracking.');
    }
}