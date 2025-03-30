@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Crear Producto</h1>
    <form action="{{ route('agricultor.guardar-producto') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre del Producto</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="precio">Precio</label>
            <input type="number" name="precio" id="precio" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="cantidad_disponible">Cantidad Disponible</label>
            <input type="number" name="cantidad_disponible" id="cantidad_disponible" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Guardar Producto</button>
    </form>
</div>
@endsection