@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Título principal -->
    <h1 class="text-center fw-bold text-success mb-4">Panel de Administración</h1>

    <!-- Opciones principales -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <a href="{{ route('admin.pedidos') }}" class="btn btn-outline-success w-100 rounded-pill shadow-sm py-3">
                Gestionar Pedidos
            </a>
        </div>
        <div class="col-md-6 mb-3">
            <a href="{{ route('admin.usuarios') }}" class="btn btn-success w-100 rounded-pill shadow-sm py-3">
                Gestionar Usuarios
            </a>
        </div>    
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <a href="{{ route('tracking.index') }}" class="btn btn-outline-success w-100 rounded-pill shadow-sm py-3">
                Seguimiento de Cultivos
            </a>
        </div>
    </div>
</div>
@endsection
