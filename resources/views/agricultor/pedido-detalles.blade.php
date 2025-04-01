{{-- filepath: c:\Users\marti\test-app\resources\views\agricultor\pedido-detalles.blade.php --}}
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
            <p><strong>Estado:</strong> {{ ucfirst($pedido->estado) }}</p>
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
            @foreach ($pedido->productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->pivot->cantidad }}</td>
                    <td>${{ number_format($producto->pivot->precio, 2) }}</td>
                    <td>${{ number_format($producto->pivot->cantidad * $producto->pivot->precio, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection