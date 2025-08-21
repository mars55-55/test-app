{{-- filepath: resources/views/tracking/index.blade.php --}}
@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4">Seguimiento de Cultivos — Sabana de Occidente / Bogotá</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($recomendado)
        <div class="alert alert-info">
            <strong>Recomendación (este mes):</strong> {{ $recomendado }}
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card p-3">
                <h5>Productos sembrados vs Precio promedio (región)</h5>
                <canvas id="chartProdPrecio" height="120"></canvas>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h6>Filtrar serie temporal</h6>
                <form method="GET" action="{{ route('tracking.index') }}">
                    <div class="mb-2">
                        <label class="form-label small">Producto</label>
                        <select name="producto" class="form-select">
                            <option value="">-- seleccionar --</option>
                                @foreach($productosStats as $p)
                                    @php $opt = $p->producto; @endphp
                                    <option value="{{ $opt }}" @if(mb_strtolower($selectedProduct ?? '') == mb_strtolower($opt)) selected @endif>{{ $opt }}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Rango (YYYY-MM)</label>
                        <input type="text" name="start" class="form-control mb-1" placeholder="2023-01" value="{{ request('start') }}">
                        <input type="text" name="end" class="form-control" placeholder="2023-12" value="{{ request('end') }}">
                    </div>
                    <button class="btn btn-sm btn-outline-success">Aplicar</button>
                </form>
            </div>

            <div class="card mt-3 p-3">
                <h6>Promedio Región</h6>
                <p class="mb-0">Total registros: <strong>{{ $trackings->count() }}</strong></p>
                <p class="mb-0">Productos únicos: <strong>{{ $productosStats->count() }}</strong></p>
            </div>
        </div>
    </div>

    <div class="card p-3 mb-4">
        <h5>Serie temporal — Producto: {{ $selectedProduct ?? 'N/A' }}</h5>
        @if($serie->isEmpty())
            <div class="alert alert-warning">No hay datos de serie temporal para el producto en el rango seleccionado.</div>
        @else
            <canvas id="chartSerie" height="80"></canvas>
        @endif
    </div>

    <h5 class="mt-4">Listado (solo lectura)</h5>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Departamento / Ciudad</th>
                <th>Fecha precio</th>
                <th>Precio prom.</th>
                <th>Fecha siembra</th>
                <th>Fecha cosecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trackings as $t)
            <tr>
                <td>{{ $t->producto }}</td>
                <td>{{ $t->departamento ?? $t->ciudad }}</td>
                <td>{{ $t->fecha_precio }}</td>
                <td>{{ $t->estimacion_ganancia ? '$' . number_format($t->estimacion_ganancia,0) : '' }}</td>
                <td>{{ $t->fecha_siembra }}</td>
                <td>{{ $t->fecha_cosecha }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Datos para Productos sembrados vs Precio
    const prodLabels = @json($productosStats->pluck('producto'));
    const prodSembrados = @json($productosStats->pluck('sembrados'));
    const prodPrecios = @json($productosStats->pluck('precio_promedio'));

    const ctx1 = document.getElementById('chartProdPrecio').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: prodLabels,
            datasets: [
                {
                    label: 'Sembrados (cantidad)',
                    data: prodSembrados,
                    backgroundColor: 'rgba(54,162,235,0.6)',
                    yAxisID: 'y',
                },
                {
                    label: 'Precio promedio (COP)',
                    data: prodPrecios,
                    type: 'line',
                    borderColor: 'rgba(255,99,132,0.9)',
                    backgroundColor: 'rgba(255,99,132,0.2)',
                    yAxisID: 'y2',
                }
            ]
        },
        options: {
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: { type: 'linear', position: 'left', title: { display:true, text:'Cantidad' } },
                y2: { type: 'linear', position: 'right', title: { display:true, text:'Precio (COP)' }, grid: { drawOnChartArea: false } }
            }
        }
    });

    // Serie temporal para producto seleccionado
    const serie = @json($serie->pluck('avg_price','ym'));
    const serieLabels = Object.keys(serie);
    const serieData = Object.values(serie);

    if(serieLabels.length === 0){
        // no dibujar el chart si no hay datos
    } else {
        const ctx2 = document.getElementById('chartSerie').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: serieLabels,
                datasets: [{
                    label: 'Precio promedio (COP)',
                    data: serieData,
                    borderColor: 'rgba(75,192,192,1)',
                    backgroundColor: 'rgba(75,192,192,0.2)',
                    tension: 0.2
                }]
            },
            options: {
                scales: {
                    y: { title: { display:true, text:'Precio (COP)' } }
                }
            }
        });
    }
</script>
@endsection