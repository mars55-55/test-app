@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center fw-bold text-success mb-4">Crear Producto</h1>
    <form action="{{ route('agricultor.guardar-producto') }}" method="POST" class="shadow-sm p-4 rounded bg-light">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label fw-bold text-success">Nombre del Producto</label>
            <input type="text" name="nombre" id="nombre" class="form-control border-success" placeholder="Ingresa el nombre del producto" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label fw-bold text-success">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control border-success" rows="3" placeholder="Describe el producto"></textarea>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label fw-bold text-success">Precio</label>
            <input type="number" name="precio" id="precio" class="form-control border-success" step="0.01" placeholder="Ingresa el precio" required>
        </div>
        <div class="mb-3">
            <label for="cantidad_disponible" class="form-label fw-bold text-success">Cantidad Disponible</label>
            <input type="number" name="cantidad_disponible" id="cantidad_disponible" class="form-control border-success" placeholder="Ingresa la cantidad disponible" required>
        </div>
        <button type="submit" class="btn btn-success w-100 rounded-pill shadow-sm">Guardar Producto</button>
    </form>
</div>
@endsection