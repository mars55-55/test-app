@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center fw-bold text-success mb-4">Dashboard del Comprador</h1>

    <!-- Sección de Pedidos -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h2 class="h5 fw-bold">Mis Pedidos</h2>
        </div>
        <div class="card-body">
            @if ($pedidos->isEmpty())
                <p class="text-muted">No tienes pedidos registrados.</p>
            @else
                <ul class="list-group">
                    @foreach ($pedidos as $pedido)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Pedido #{{ $pedido->id }} - Total: ${{ number_format($pedido->total, 2) }}</span>
                            <span class="badge bg-success">{{ $pedido->estado }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Sección de Productos Disponibles -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h2 class="h5 fw-bold">Productos Disponibles</h2>
        </div>
        <div class="card-body">
            @if ($productos->isEmpty())
                <p class="text-muted">No hay productos disponibles en este momento.</p>
            @else
                <ul class="list-group">
                    @foreach ($productos as $producto)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $producto->nombre }}</span>
                            <span class="fw-bold text-success">Precio: ${{ number_format($producto->precio, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection