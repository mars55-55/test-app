<!-- filepath: c:\Users\User\prueba Final\test-app\resources\views\admin\pedidos.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h1 class="text-center fw-bold text-success mb-4">Historial de Pedidos</h1>

    @if ($pedidos->isEmpty())
        <p class="text-muted text-center">No hay pedidos registrados.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="bg-success text-white">
                    <tr>
                        <th>ID</th>
                        <th>Comprador</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Productos</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->usuario->name }}</td>
                            <td class="fw-bold text-success">${{ $pedido->total }}</td>
                            <td>
                                <span class="badge 
                                    @if($pedido->estado === 'pendiente') bg-warning text-dark 
                                    @elseif($pedido->estado === 'completado') bg-success 
                                    @else bg-danger 
                                    @endif">
                                    {{ ucfirst($pedido->estado) }}
                                </span>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($pedido->productos as $producto)
                                        <li>{{ $producto->nombre }} <span class="text-muted">(Cantidad: {{ $producto->pivot->cantidad }})</span></li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection