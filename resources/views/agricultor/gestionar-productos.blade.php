@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Título principal -->
    <h1 class="text-center fw-bold text-success mb-4">Mis Productos</h1>

    <!-- Botón para crear producto -->
    <div class="text-end mb-4">
        <a href="{{ route('agricultor.crear-producto') }}" class="btn btn-success rounded-pill shadow-sm px-4 py-2">
            Crear Producto
        </a>
    </div>

    <!-- Tabla de productos -->
    <div class="table-responsive">
        <table class="table table-hover shadow-sm">
            <thead class="bg-success text-white">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cantidad Disponible</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->descripcion }}</td>
                    <td>${{ $producto->precio }}</td>
                    <td>{{ $producto->cantidad_disponible }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection