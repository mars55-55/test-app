{{-- filepath: resources/views/admin/usuario-editar.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Título principal -->
    <h1 class="text-center fw-bold text-success mb-4">Editar Usuario</h1>

    <form action="{{ route('admin.usuarios.actualizar', $usuario->id) }}" method="POST" class="shadow-sm p-4 bg-light rounded">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label fw-bold text-success">Nombre</label>
            <input type="text" class="form-control border-success" id="name" name="name" value="{{ $usuario->name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-bold text-success">Email</label>
            <input type="email" class="form-control border-success" id="email" name="email" value="{{ $usuario->email }}" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label fw-bold text-success">Rol</label>
            <select class="form-control border-success" id="role" name="role_id" required>
                @foreach ($roles as $rol)
                    <option value="{{ $rol->id }}" {{ $usuario->role_id == $rol->id ? 'selected' : '' }}>
                        {{ $rol->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success w-100 rounded-pill">Guardar Cambios</button>
    </form>
</div>
@endsection