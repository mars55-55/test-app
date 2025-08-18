<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AgricultorController extends Controller
{
    public function dashboard(Request $request)
    {
        $productosSabanaOccidente = [
            'Papa criolla', 'Papa pastusa', 'Papa sabanera', 'Zanahoria', 'Cebolla larga', 'Lechuga', 'Brócoli', 'Coliflor', 'Habichuela', 'Arveja', 'Fresa', 'Maíz', 'Repollo'
        ];

        $cultivos = \DB::table('tracking')
            ->select('producto')
            ->whereIn('producto', $productosSabanaOccidente)
            ->distinct()
            ->pluck('producto');

        $productos = \App\Models\Producto::where('agricultor_id', auth()->id())
            ->whereIn('nombre', $productosSabanaOccidente)
            ->get();

        $recomendaciones = [];

        if ($request->has('fecha_siembra')) {
            $fechaSiembra = \Carbon\Carbon::parse($request->fecha_siembra);
            $mesSiembra = $fechaSiembra->format('m');

            foreach ($cultivos as $cultivoNombre) {
                // Solo toma históricos del mismo mes de siembra
                $historicos = \DB::table('tracking')
                    ->where('producto', $cultivoNombre)
                    ->whereNotNull('fecha_siembra')
                    ->whereNotNull('fecha_cosecha')
                    ->get()
                    ->filter(function($h) use ($mesSiembra) {
                        return \Carbon\Carbon::parse($h->fecha_siembra)->format('m') == $mesSiembra;
                    });

                // Si no hay históricos de ese mes, usa todos
                if ($historicos->count() == 0) {
                    $historicos = \DB::table('tracking')
                        ->where('producto', $cultivoNombre)
                        ->whereNotNull('fecha_siembra')
                        ->whereNotNull('fecha_cosecha')
                        ->get();
                }

                $diasCosecha = 60;
                if ($historicos->count() > 0) {
                    $totalDias = 0;
                    $conteo = 0;
                    foreach ($historicos as $h) {
                        $dias = \Carbon\Carbon::parse($h->fecha_siembra)->diffInDays(\Carbon\Carbon::parse($h->fecha_cosecha));
                        $totalDias += $dias;
                        $conteo++;
                    }
                    $diasCosecha = round($totalDias / $conteo);
                }

                $fechaCosecha = $fechaSiembra->copy()->addDays($diasCosecha);

                // Buscar ganancia histórica más cercana a la fecha de cosecha
                $precioRow = \DB::table('tracking')
                    ->where('producto', $cultivoNombre)
                    ->whereNotNull('estimacion_ganancia')
                    ->orderByRaw('ABS(DATEDIFF(fecha_precio, ?))', [$fechaCosecha->toDateString()])
                    ->first();

                $ganancia = $precioRow->estimacion_ganancia ?? 0;

                $climas = ['Soleado', 'Lluvia', 'Nublado', 'Tormenta', 'Parcialmente nublado'];
                $indiceClima = intval($fechaSiembra->format('d')) % count($climas);
                $clima = $climas[$indiceClima];

                $recomendaciones[] = [
                    'cultivo' => $cultivoNombre,
                    'ganancia' => $ganancia,
                    'dias_cosecha' => $diasCosecha,
                    'fecha_cosecha' => $fechaCosecha->toDateString(),
                    'fuente' => $precioRow->fuente ?? 'N/A',
                    'clima' => $clima,
                ];
            }

            // Ordenar por ganancia descendente y tomar el top 3
            $recomendaciones = collect($recomendaciones)
                ->sortByDesc('ganancia')
                ->take(3)
                ->values()
                ->all();
        }

        return view('agricultor.dashboard', compact('cultivos', 'productos', 'recomendaciones'));
    }

    public function index(Request $request)
    {
        // Obtener cultivos y productos del agricultor
        $cultivos = \App\Models\Producto::all();
        $productos = \App\Models\Producto::where('agricultor_id', auth()->id())->get();

        $resultadoPlanificacion = null;

        if ($request->has(['cultivo', 'fecha_venta'])) {
            $cultivo = \App\Models\Producto::find($request->cultivo);

            // 1. Obtener tiempo promedio de cosecha (puedes tener un campo en la tabla o calcularlo)
            $dias_cosecha = $cultivo->dias_cosecha ?? 60; // ejemplo: 60 días si no existe el campo

            // 2. Calcular fecha recomendada de siembra
            $fecha_venta = \Carbon\Carbon::parse($request->fecha_venta);
            $fecha_siembra = $fecha_venta->copy()->subDays($dias_cosecha);

            // 3. Buscar el mejor precio histórico para ese cultivo cerca de la fecha de venta
            $precio_estimado = \DB::table('precios_historicos')
                ->where('producto_id', $cultivo->id)
                ->whereDate('fecha', '<=', $fecha_venta)
                ->orderByDesc('precio')
                ->value('precio') ?? $cultivo->precio;

            // 4. Simular pronóstico del clima (puedes integrar una API real)
            $clima = 'Soleado'; // ejemplo fijo

            $resultadoPlanificacion = [
                'fecha_siembra' => $fecha_siembra->toDateString(),
                'dias_cosecha' => $dias_cosecha,
                'precio_estimado' => $precio_estimado,
                'clima' => $clima,
            ];
        }

        return view('agricultor.dashboard', compact('cultivos', 'productos', 'resultadoPlanificacion'));
    }

    public function gestionarProductos()
    {
        $user = Auth::user();

        if (!$user || $user->role->name !== 'agricultor') {
            return redirect('/acceso-denegado')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        $productos = Producto::where('agricultor_id', $user->id)->get();

        return view('agricultor.gestionar-productos', compact('productos'));
    }

    public function crearProducto()
    {
        return view('agricultor.crear-producto');
    }

    public function guardarProducto(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'cantidad_disponible' => 'required|integer|min:1',
        ]);

        Producto::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'precio' => $validated['precio'],
            'cantidad_disponible' => $validated['cantidad_disponible'],
            'agricultor_id' => $user->id,
        ]);

        return redirect()->route('agricultor.gestionar-productos')->with('success', 'Producto creado con éxito.');
    }

    public function editarProducto($id)
    {
        $producto = Producto::findOrFail($id);
        return view('agricultor.editar-producto', compact('producto'));
    }

    public function actualizarProducto(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'cantidad_disponible' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update($validated);

        return redirect()->route('agricultor.gestionar-productos')->with('success', 'Producto actualizado con éxito.');
    }

    public function eliminarProducto($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('agricultor.gestionar-productos')->with('success', 'Producto eliminado con éxito.');
    }

    public function pedidosRecibidos()
    {
        // Obtener los pedidos relacionados con el agricultor autenticado
        $pedidos = Pedido::whereHas('productos', function ($query) {
            $query->where('agricultor_id', auth()->id());
        })->get();

        return view('agricultor.pedidos-recibidos', compact('pedidos'));
    }

    public function detallesPedido($id)
    {
        $pedido = Pedido::with('productos')->findOrFail($id);

        // Verificar que el pedido esté relacionado con el agricultor autenticado
        if (!$pedido->productos->where('agricultor_id', auth()->id())->count()) {
            abort(403, 'No tienes permiso para ver este pedido.');
        }

        return view('agricultor.pedido-detalles', compact('pedido'));
    }
}
