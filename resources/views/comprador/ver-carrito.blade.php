@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center fw-bold text-success mb-4">Mi Carrito</h1>
    @if (session('carrito') && count(session('carrito')) > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm">
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
                    <td>{{ $producto['nombre'] }}</td>
                    <td class="fw-bold text-success">${{ number_format($producto['precio'], 2) }}</td>
                    <td>{{ $producto['cantidad'] }}</td>
                    <td class="fw-bold text-success">${{ number_format($producto['precio'] * $producto['cantidad'], 2) }}</td>
                </tr>
                @php $total += $producto['precio'] * $producto['cantidad']; @endphp
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="text-end">
        <h3 class="fw-bold text-success">Total: ${{ number_format($total, 2) }}</h3>
        <form action="{{ route('comprador.realizar-pedido') }}" method="POST" class="d-inline-block">
            @csrf
            <button type="submit" class="btn btn-success rounded-pill shadow-sm px-4 py-2">Realizar Pedido</button>
        </form>
    </div>
    @else
    <p class="text-center text-muted">Tu carrito está vacío.</p>
    @endif
</div>
@endsection