@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="fw-bold text-success mb-3">Sembrar / Cultivar (Sabana de Occidente)</h1>

    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('agricultor.sembrar.recomendar') }}">
                @csrf
                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de inicio de cultivo</label>
                    <input id="fecha_inicio" name="fecha_inicio" type="date" class="form-control" value="{{ old('fecha_inicio', $fecha_inicio ?? '') }}" required>
                    @error('fecha_inicio') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <button class="btn btn-success">Calcular recomendaciones</button>
            </form>
        </div>
    </div>

    @if(isset($clima))
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>Clima estimado (mes seleccionado)</h5>
                    <p>Temperatura promedio: <strong>{{ $clima['avg_temp'] }} °C</strong></p>
                    <p>Precipitación total: <strong>{{ $clima['total_precip'] }} mm</strong></p>
                    <p class="small text-muted">Datos aproximados para la Sabana de Occidente (coords: 4.7639, -74.3459).</p>
                </div>
            </div>
        </div>
    @endif

    @if(!empty($recomendaciones) && $recomendaciones->count())
        <h3>Recomendaciones para {{ $fecha_inicio }}</h3>
        <p class="text-muted">Ordenadas por puntaje (frecuencia histórica, ganancia y duración).</p>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Frecuencia</th>
                    <th>Ganancia prom.</th>
                    <th>Duración (días)</th>
                    <th>Fecha cosecha estimada</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recomendaciones as $i => $r)
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $r->producto }}</td>
                    <td>{{ $r->frecuencia }}</td>
                    <td>${{ number_format($r->ganancia_promedio ?? 0, 0) }}</td>
                    <td>{{ round($r->estimacion_dias) }}</td>
                    <td>{{ $r->fecha_cosecha_estimada }}</td>
                    <td>
                        <form method="POST" action="{{ route('agricultor.sembrar.confirmar') }}">
                            @csrf
                            <input type="hidden" name="producto" value="{{ $r->producto }}">
                            <input type="hidden" name="fecha_inicio" value="{{ $fecha_inicio }}">
                            <input type="hidden" name="estimacion_dias" value="{{ round($r->estimacion_dias) }}">
                            <input type="hidden" name="estimacion_ganancia" value="{{ round($r->ganancia_promedio ?? 0) }}">
                                <p class="small text-muted">Datos aproximados para la Sabana de Occidente (coords: 4.7639, -74.3459).</p>
                            <button class="btn btn-primary btn-sm">Seleccionar y Confirmar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @elseif(isset($fecha_inicio))
        <div class="alert alert-info">No hay recomendaciones suficientes para la fecha seleccionada.</div>
    @endif
</div>
@endsection