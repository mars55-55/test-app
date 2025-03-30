@extends('layouts.app')

@section('content')
<h1>Mis Productos</h1>
<a href="{{ route('agricultor.crear-producto') }}" class="btn btn-primary">Crear Producto</a>
<table>
    <thead>
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
            <td>{{ $producto->precio }}</td>
            <td>{{ $producto->cantidad_disponible }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection