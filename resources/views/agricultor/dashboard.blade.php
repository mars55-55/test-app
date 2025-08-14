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