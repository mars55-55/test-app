@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Título -->
    <h1 class="text-center fw-bold text-success mb-4">Productos Disponibles</h1>
    <div class="row">
        @foreach ($productos as $producto)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold text-success">{{ $producto->nombre }}</h5>
                    <p class="card-text text-muted">{{ $producto->descripcion }}</p>
                    <p class="card-text"><strong>Precio:</strong> <span class="fw-bold text-success">${{ $producto->precio }}</span></p>
                    <p class="card-text"><strong>Disponibles:</strong> {{ $producto->cantidad_disponible }}</p>
                    <form action="{{ route('comprador.agregar-al-carrito', $producto->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-success rounded-pill px-4 py-2">Agregar al Carrito</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection