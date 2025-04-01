@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Título -->
    <h1 class="text-center fw-bold text-success mb-4">Mi Carrito</h1>

    @if (session('carrito') && count(session('carrito')) > 0)
    <!-- Tabla de productos -->
    <table class="table table-hover shadow-sm">
        <thead class="bg-success text-white">
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach (session('carrito') as $id => $producto)
            <tr>
                <td class="fw-bold">{{ $producto['nombre'] }}</td>
                <td>${{ $producto['precio'] }}</td>
                <td>{{ $producto['cantidad'] }}</td>
                <td class="fw-bold text-success">${{ $producto['precio'] * $producto['cantidad'] }}</td>
            </tr>
            @php $total += $producto['precio'] * $producto['cantidad']; @endphp
            @endforeach
        </tbody>
    </table>

    <!-- Total -->
    <div class="text-end">
        <h3 class="fw-bold text-success">Total: ${{ $total }}</h3>
    </div>

    <!-- Botón para realizar pedido -->
    <div class="text-center mt-4">
        <form action="{{ route('comprador.realizar-pedido') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success btn-lg rounded-pill shadow-lg px-4 py-2">Realizar Pedido</button>
        </form>
    </div>
    @else
    <!-- Mensaje de carrito vacío -->
    <p class="text-center text-muted">Tu carrito está vacío.</p>
    @endif
</div>
@endsection