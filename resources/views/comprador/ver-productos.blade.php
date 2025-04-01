@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center fw-bold text-success mb-4">Productos Disponibles</h1>
    <div class="row">
        @foreach ($productos as $producto)
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-success">{{ $producto->nombre }}</h5>
                    <p class="card-text text-muted">{{ $producto->descripcion }}</p>
                    <p class="card-text"><strong>Precio:</strong> <span class="text-success">${{ number_format($producto->precio, 2) }}</span></p>
                    <p class="card-text"><strong>Disponibles:</strong> {{ $producto->cantidad_disponible }}</p>
                    <form action="{{ route('comprador.agregar-al-carrito', $producto->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 rounded-pill shadow-sm">Agregar al Carrito</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection