@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Productos Disponibles</h1>
    <div class="row">
        @foreach ($productos as $producto)
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">{{ $producto->nombre }}</h5>
                    <p class="card-text">{{ $producto->descripcion }}</p>
                    <p class="card-text"><strong>Precio:</strong> ${{ $producto->precio }}</p>
                    <p class="card-text"><strong>Disponibles:</strong> {{ $producto->cantidad_disponible }}</p>
                    <form action="{{ route('comprador.agregar-al-carrito', $producto->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Agregar al Carrito</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection