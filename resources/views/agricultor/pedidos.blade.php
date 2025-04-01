{{-- filepath: resources/views/admin/pedidos.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <!-- Título principal -->
    <h1 class="text-center fw-bold text-success mb-4">Gestión de Pedidos</h1>

    @if ($pedidos->isEmpty())
        <div class="alert alert-info text-center">
            No hay pedidos registrados.
        </div>
    @else
        <!-- Tabla de pedidos -->
        <div class="table-responsive">
            <table class="table table-hover shadow-sm">
                <thead class="bg-success text-white">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->cliente->name ?? 'Cliente no registrado' }}</td>
                            <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                            <td><span class="fw-bold text-success">${{ number_format($pedido->total, 2) }}</span></td>
                            <td><span class="badge bg-primary">{{ ucfirst($pedido->estado) }}</span></td>
                            <td>
                                <a href="{{ route('agricultor.pedidos.detalles', $pedido->id) }}" class="btn btn-sm btn-outline-success rounded-pill">Ver Detalles</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

{{-- filepath: resources/views/admin/pedido-detalles.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Título principal -->
    <h1 class="text-center fw-bold text-success mb-4">Detalles del Pedido #{{ $pedido->id }}</h1>

    <!-- Información del Pedido -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white fw-bold">
            Información del Pedido
        </div>
        <div class="card-body">
            <p><strong>Cliente:</strong> {{ $pedido->cliente->name ?? 'Cliente no registrado' }}</p>
            <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Total:</strong> <span class="fw-bold text-success">${{ number_format($pedido->total, 2) }}</span></p>
            <p><strong>Estado:</strong> <span class="badge bg-primary">{{ ucfirst($pedido->estado) }}</span></p>
        </div>
    </div>

    <!-- Lista de Productos -->
    <h3 class="fw-bold text-success mb-3">Productos</h3>
    <div class="table-responsive">
        <table class="table table-hover shadow-sm">
            <thead class="bg-success text-white">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedido->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>${{ number_format($detalle->precio, 2) }}</td>
                        <td>${{ number_format($detalle->cantidad * $detalle->precio, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
