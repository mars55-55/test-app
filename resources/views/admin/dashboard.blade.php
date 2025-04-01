@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center fw-bold text-success mb-4">Panel de Administración</h1>
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <!-- Enlace para gestionar productos -->
            <a href="{{ route('admin.productos') }}" class="btn btn-success w-100 rounded-pill shadow-sm py-3">
                Gestionar Productos
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <!-- Enlace para gestionar pedidos -->
            <a href="{{ route('admin.pedidos') }}" class="btn btn-outline-success w-100 rounded-pill shadow-sm py-3">
                Gestionar Pedidos
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <!-- Enlace para gestionar usuarios -->
            <a href="{{ route('admin.usuarios') }}" class="btn btn-success w-100 rounded-pill shadow-sm py-3">
                Gestionar Usuarios
            </a>
        </div>
    </div>
</div>
@endsection
