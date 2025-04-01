{{-- filepath: c:\Users\User\prueba_Final\test-app\resources\views\agricultor\editar-producto.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center fw-bold text-success mb-4">Editar Producto</h1>
    <form action="{{ route('agricultor.actualizar-producto', $producto->id) }}" method="POST" class="shadow-sm p-4 bg-light rounded">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nombre" class="form-label fw-bold text-success">Nombre del Producto</label>
            <input type="text" name="nombre" id="nombre" class="form-control border-success" value="{{ $producto->nombre }}" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label fw-bold text-success">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control border-success">{{ $producto->descripcion }}</textarea>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label fw-bold text-success">Precio</label>
            <input type="number" name="precio" id="precio" class="form-control border-success" value="{{ $producto->precio }}" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="cantidad_disponible" class="form-label fw-bold text-success">Cantidad Disponible</label>
            <input type="number" name="cantidad_disponible" id="cantidad_disponible" class="form-control border-success" value="{{ $producto->cantidad_disponible }}" required>
        </div>
        <button type="submit" class="btn btn-success w-100 rounded-pill">Actualizar Producto</button>
    </form>
</div>
@endsection