@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Título -->
    <h1 class="text-center fw-bold text-success mb-4">Dashboard del Comprador</h1>

    <!-- Sección de Pedidos -->
    <div class="card mb-5 shadow-sm">
        <div class="card-header text-white bg-success">
            <h2 class="card-title m-0">Mis Pedidos</h2>
        </div>
        <div class="card-body">
            @if ($pedidos->isEmpty())
                <p class="text-muted">No tienes pedidos registrados.</p>
            @else
                <div class="mb-4">
                    <form action="{{ route('comprador.pagar-pedidos') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Pagar Todos los Pedidos Pendientes</button>
                    </form>
                </div>

                <ul class="list-group" id="pedido-list">
                    @foreach ($pedidos as $pedido)
                        <li class="list-group-item d-flex justify-content-between align-items-center" id="pedido-{{ $pedido->id }}">
                            <div>
                                <span>Pedido #{{ $pedido->id }} - Total: <span class="fw-bold text-success">${{ $pedido->total }}</span></span>
                                <span class="badge 
                                    @if($pedido->estado === 'pendiente') bg-warning text-dark 
                                    @elseif($pedido->estado === 'completado') bg-success 
                                    @else bg-danger 
                                    @endif">
                                    {{ ucfirst($pedido->estado) }}
                                </span>
                            </div>
                            @if ($pedido->estado === 'pendiente')
                                <form action="{{ route('comprador.cancelar-pedido', $pedido->id) }}" method="POST" class="ms-3">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                                </form>
                            @elseif ($pedido->estado === 'cancelado')
                                <button class="btn btn-outline-danger btn-sm ms-3" onclick="eliminarPedido({{ $pedido->id }})">Eliminar</button>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Sección de Productos Disponibles -->
    <div class="card shadow-sm">
        <div class="card-header text-white bg-success">
            <h2 class="card-title m-0">Productos Disponibles</h2>
        </div>
        <div class="card-body">
            @if ($productos->isEmpty())
                <p class="text-muted">No hay productos disponibles en este momento.</p>
            @else
                <div class="row">
                    @foreach ($productos as $producto)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    <h4 class="card-title fw-bold text-success">{{ $producto->nombre }}</h4>
                                    <p class="card-text">Precio: <span class="fw-bold text-success">${{ $producto->precio }}</span></p>
                                    <p class="card-text text-muted">Cantidad disponible: <span class="fw-bold">{{ $producto->cantidad_disponible }}</span></p>
                                    <form action="{{ route('comprador.comprar', $producto->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-2">
                                            <label for="cantidad_{{ $producto->id }}" class="form-label">Cantidad:</label>
                                            <input type="number" name="cantidad" id="cantidad_{{ $producto->id }}" class="form-control" value="1" min="1" max="{{ $producto->cantidad_disponible }}" required>
                                        </div>
                                        <button type="submit" class="btn btn-outline-success">Comprar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Script para eliminar pedidos cancelados -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Ocultar pedidos eliminados al cargar la página
        const pedidosEliminados = JSON.parse(localStorage.getItem('pedidosEliminados')) || [];
        pedidosEliminados.forEach(pedidoId => {
            const pedidoElement = document.getElementById(`pedido-${pedidoId}`);
            if (pedidoElement) {
                pedidoElement.remove();
            }
        });
    });

    function eliminarPedido(pedidoId) {
        // Confirmación antes de eliminar visualmente
        if (confirm('¿Estás seguro de que deseas eliminar este pedido de la vista?')) {
            // Selecciona el elemento del pedido por su ID
            const pedidoElement = document.getElementById(`pedido-${pedidoId}`);
            if (pedidoElement) {
                // Elimina el elemento del DOM
                pedidoElement.remove();

                // Guardar el ID del pedido eliminado en localStorage
                let pedidosEliminados = JSON.parse(localStorage.getItem('pedidosEliminados')) || [];
                if (!pedidosEliminados.includes(pedidoId)) {
                    pedidosEliminados.push(pedidoId);
                    localStorage.setItem('pedidosEliminados', JSON.stringify(pedidosEliminados));
                }

                alert('El pedido ha sido eliminado visualmente.');
            } else {
                alert('No se pudo encontrar el pedido.');
            }
        }
    }
</script>
@endsection