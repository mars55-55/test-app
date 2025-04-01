@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center fw-bold text-success mb-4">Pedidos Recibidos</h1>
    @if ($pedidos->isEmpty())
        <p class="text-center text-muted">No tienes pedidos relacionados con tus productos.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm">
                <thead class="bg-success text-white">
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
                        <td class="fw-bold">{{ $pedido->id }}</td>
                        <td>
                            <ul class="list-unstyled mb-0">
                                @foreach ($pedido->productos as $producto)
                                    <li>{{ $producto->nombre }} <span class="badge bg-success">x{{ $producto->pivot->cantidad }}</span></li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="fw-bold text-success">${{ number_format($pedido->total, 2) }}</td>
                        <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection