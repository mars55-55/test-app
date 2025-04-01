@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center fw-bold text-success mb-4">Panel del Agricultor</h1>

    <!-- Opciones de Gestión -->
    <div class="row mb-5">
        <div class="col-md-6">
            <a href="{{ route('agricultor.gestionar-productos') }}" class="btn btn-success w-100 rounded-pill shadow-sm py-3">
                Gestionar Mis Productos
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('agricultor.pedidos-recibidos') }}" class="btn btn-outline-success w-100 rounded-pill shadow-sm py-3">
                Ver Pedidos Recibidos
            </a>
        </div>
    </div>

    <!-- Sección de Productos -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h2 class="h5 fw-bold">Mis Productos</h2>
        </div>
        <div class="card-body">
            @if ($productos->isEmpty())
                <p class="text-muted">No tienes productos registrados.</p>
            @else
                <ul class="list-group">
                    @foreach ($productos as $producto)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $producto->nombre }}</span>
                            <span class="badge bg-success">{{ $producto->cantidad_disponible }} disponibles</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection