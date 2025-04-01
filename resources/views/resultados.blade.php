<!-- filepath: c:\Users\User\prueba Final\test-app\resources\views\resultados.blade.php -->
@extends('layouts.app')

@section('title', 'Resultados de Búsqueda')

@section('content')
<div class="container mt-5">
    <h1 class="text-center fw-bold text-success mb-4">Resultados de Búsqueda</h1>
    <p class="text-muted text-center">Resultados para: <span class="fw-bold">"{{ $query }}"</span></p>

    @if ($resultados->isEmpty())
        <p class="text-muted text-center">No se encontraron resultados.</p>
    @else
        <div class="row">
            @foreach ($resultados as $resultado)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            @if (isset($resultado->nombre))
                                <h5 class="card-title">{{ $resultado->nombre }}</h5>
                                <p class="card-text">{{ $resultado->descripcion }}</p>
                                <p class="text-success fw-bold">Precio: ${{ $resultado->precio }}</p>
                                <p class="text-muted">Cantidad disponible: {{ $resultado->cantidad_disponible }}</p>

                                <!-- Formulario para comprar el producto -->
                                <form action="{{ route('comprador.comprar', $resultado->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="cantidad_{{ $resultado->id }}" class="form-label">Cantidad:</label>
                                        <input type="number" name="cantidad" id="cantidad_{{ $resultado->id }}" class="form-control" value="1" min="1" max="{{ $resultado->cantidad_disponible }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">Comprar</button>
                                </form>
                            @elseif (isset($resultado->id))
                                <h5 class="card-title">Pedido #{{ $resultado->id }}</h5>
                                <p class="card-text">Comprador: {{ $resultado->usuario->name }}</p>
                                <p class="text-success fw-bold">Total: ${{ $resultado->total }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection