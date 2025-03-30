@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Pedidos Recibidos</h1>
    @if ($pedidos->isEmpty())
        <p class="text-center">No tienes pedidos relacionados con tus productos.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID del Pedido</th>
                    <th>Productos</th>
                    <th>Total</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                <tr>
                    <td>{{ $pedido->id }}</td>
                    <td>
                        <ul>
                            @foreach ($pedido->productos as $producto)
                                <li>{{ $producto->nombre }} (Cantidad: {{ $producto->pivot->cantidad }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>${{ $pedido->total }}</td>
                    <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection