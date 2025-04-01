@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center fw-bold text-success mb-4">Mis Productos</h1>
    <div class="mb-4 text-end">
        <a href="{{ route('agricultor.crear-producto') }}" class="btn btn-success rounded-pill shadow-sm px-4 py-2">
            Crear Producto
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm">
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
                    <td class="fw-bold text-success">${{ number_format($producto->precio, 2) }}</td>
                    <td>{{ $producto->cantidad_disponible }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection