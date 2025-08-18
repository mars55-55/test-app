@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Título principal -->
    <h1 class="text-center fw-bold text-success mb-4">Panel del Agricultor</h1>

    <!-- Opciones principales -->
    <div class="row mb-5">
        <div class="col-md-6">
            <a href="{{ route('agricultor.gestionar-productos') }}" class="btn btn-success w-100 rounded-pill shadow-sm py-3">
                Gestionar Mis Productos
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('agricultor.pedidos-recibidos') }}" class="btn btn-outline-success w-100 rounded-pill shadow-sm py-3">
                Ver Pedidos Enviados
            </a>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-6">
            <a href="{{ route('tracking.index') }}" class="btn btn-outline-success w-100 rounded-pill shadow-sm py-3">
                Seguimiento de Cultivos
            </a>
        </div>
    </div>

    <!-- Módulo: Recomendación de Cultivo -->
    <div class="alert alert-info mb-4">
        <h5 class="fw-bold">¿Qué sembrar según tu fecha de inicio? (Solo productos de Cundinamarca)</h5>
        <form method="GET" action="{{ route('agricultor.dashboard') }}">
            <div class="row align-items-end">
                <div class="col-md-6">
                    <label for="fecha_siembra" class="form-label">Fecha en la que deseas sembrar</label>
                    <input type="date" name="fecha_siembra" id="fecha_siembra" class="form-control" value="{{ request('fecha_siembra') }}">
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-success mt-3">Recomendar cultivo</button>
                </div>
            </div>
        </form>
        @if(isset($recomendaciones) && count($recomendaciones) > 0)
            <div class="mt-4">
                <strong>Top 3 productos recomendados:</strong>
                <ol>
                    @foreach($recomendaciones as $rec)
                        <li>
                            <strong>{{ $rec['cultivo'] }}</strong><br>
                            Ganancia estimada: ${{ number_format($rec['ganancia'], 0) }}<br>
                            Tiempo promedio de cosecha: {{ $rec['dias_cosecha'] }} días<br>
                            Fecha estimada de cosecha: {{ $rec['fecha_cosecha'] }}<br>
                            Fuente del precio: {{ $rec['fuente'] }}<br>
                            Pronóstico del clima: {{ $rec['clima'] }}
                        </li>
                    @endforeach
                </ol>
            </div>
        @elseif(request('fecha_siembra'))
            <div class="mt-4 text-danger">
                No se encontraron recomendaciones para la fecha seleccionada.
            </div>
        @endif
    </div>

    <!-- Lista de productos -->
    <h2 class="fw-bold text-success mb-4">Mis Productos</h2>
    @if ($productos->isEmpty())
        <p class="text-muted">No tienes productos registrados.</p>
    @else
        <ul class="list-group shadow-sm">
            @foreach ($productos as $producto)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $producto->nombre }}</span>
                    <span class="badge bg-success">{{ $producto->cantidad_disponible }} disponibles</span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection