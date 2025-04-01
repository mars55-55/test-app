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
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->descripcion }}</td>
                    <td>${{ $producto->precio }}</td>
                    <td>{{ $producto->cantidad_disponible }}</td>
                    <td>
                        <a href="{{ route('agricultor.editar-producto', $producto->id) }}" class="btn btn-sm btn-outline-warning rounded-pill">Editar</a>
                        <form action="{{ route('agricultor.eliminar-producto', $producto->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection