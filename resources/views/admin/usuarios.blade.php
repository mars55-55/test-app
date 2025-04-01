{{-- filepath: resources/views/admin/usuarios.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Gestión de Usuarios</h1>

    @if ($usuarios->isEmpty())
        <div class="alert alert-info text-center">
            No hay usuarios registrados.
        </div>
    @else
        <table class="table table-striped">
            <thead>
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
                            <a href="{{ route('admin.usuarios.editar', $usuario->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('admin.usuarios.eliminar', $usuario->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
