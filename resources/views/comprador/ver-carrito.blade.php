@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Mi Carrito</h1>
    @if (session('carrito') && count(session('carrito')) > 0)
    <table class="table">
        <thead>
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
                <td>{{ $producto['nombre'] }}</td>
                <td>${{ $producto['precio'] }}</td>
                <td>{{ $producto['cantidad'] }}</td>
                <td>${{ $producto['precio'] * $producto['cantidad'] }}</td>
            </tr>
            @php $total += $producto['precio'] * $producto['cantidad']; @endphp
            @endforeach
        </tbody>
    </table>
    <h3>Total: ${{ $total }}</h3>
    <form action="{{ route('comprador.realizar-pedido') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">Realizar Pedido</button>
    </form>
    @else
    <p class="text-center">Tu carrito está vacío.</p>
    @endif
</div>
@endsection