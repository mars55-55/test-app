{{-- filepath: resources/views/admin/usuarios.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Título principal -->
    <h1 class="text-center fw-bold text-success mb-4">Gestión de Usuarios</h1>

    @if ($usuarios->isEmpty())
        <div class="alert alert-info text-center">
            No hay usuarios registrados.
        </div>
    @else
        <!-- Tabla de usuarios -->
        <div class="table-responsive">
            <table class="table table-hover shadow-sm">
                <thead class="bg-success text-white">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->role->name ?? 'Sin rol asignado' }}</td>
                            <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.usuarios.editar', $usuario->id) }}" class="btn btn-sm btn-outline-warning rounded-pill">Editar</a>
                                <form action="{{ route('admin.usuarios.eliminar', $usuario->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
