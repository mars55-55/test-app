@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Panel de Administración</h1>
    <div class="row">
        <div class="col-md-6">
            <!-- Enlace para gestionar pedidos -->
            <a href="{{ route('admin.pedidos') }}" class="btn btn-secondary w-100">Gestionar Pedidos</a>
        </div>
        <div class="col-md-6">
            <!-- Enlace para gestionar usuarios -->
            <a href="{{ route('admin.usuarios') }}" class="btn btn-success w-100">Gestionar Usuarios</a>
        </div>
    </div>
</div>
@endsection
