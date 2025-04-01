{{-- filepath: resources/views/admin/pedidos.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Gestión de Pedidos</h1>

    @if ($pedidos->isEmpty())
        <div class="alert alert-info text-center">
            No hay pedidos registrados.
        </div>
    @else
        <table class="table table-striped">
            <thead>
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
                        <td>${{ number_format($pedido->total, 2) }}</td>
                        <td>{{ ucfirst($pedido->estado) }}</td>
                        <td>
                            <a href="{{ route('agricultor.pedidos.detalles', $pedido->id) }}" class="btn btn-sm btn-primary">Ver Detalles</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

{{-- filepath: resources/views/admin/pedido-detalles.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Detalles del Pedido #{{ $pedido->id }}</h1>

    <div class="card mb-4">
        <div class="card-header">
            Información del Pedido
        </div>
        <div class="card-body">
            <p><strong>Cliente:</strong> {{ $pedido->cliente->name ?? 'Cliente no registrado' }}</p>
            <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Total:</strong> ${{ number_format($pedido->total, 2) }}</p>
            <p><strong>Estado:</strong> {{ $pedido->estado }}</p>
        </div>
    </div>

    <h3>Productos</h3>
    <table class="table table-striped">
        <thead>
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
@endsection
