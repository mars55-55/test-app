{{-- filepath: resources/views/tracking/index.blade.php --}}
@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4">Seguimiento de Cultivos</h1>

    @if(auth()->user()->role->name === 'agricultor')
        @php
            $mesActual = now()->format('m');
            $recomendado = $trackings->where('fecha_siembra', 'like', '%-' . $mesActual . '-%')->first();
        @endphp
        <div class="alert alert-info">
            <strong>Producto recomendado para sembrar este mes:</strong>
            {{ $recomendado ? $recomendado->producto : 'No hay recomendación disponible.' }}
        </div>
    @endif

    <canvas id="graficoGanancias" height="100"></canvas>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Fecha Siembra</th>
                <th>Fecha Cosecha</th>
                <th>Estimación Ganancia</th>
                <th>Notas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trackings as $tracking)
            <tr>
                <td>{{ $tracking->producto }}</td>
                <td>{{ $tracking->fecha_siembra }}</td>
                <td>{{ $tracking->fecha_cosecha }}</td>
                <td>${{ number_format($tracking->estimacion_ganancia, 0) }}</td>
                <td>{{ $tracking->notas }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficoGanancias').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($trackings->pluck('producto')),
            datasets: [{
                label: 'Estimación Ganancia',
                data: @json($trackings->pluck('estimacion_ganancia')),
                backgroundColor: 'rgba(40,167,69,0.5)'
            }]
        }
    });
</script>
@endsection