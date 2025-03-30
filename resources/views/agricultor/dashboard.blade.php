@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Panel del Agricultor</h1>
    <div class="row">
        <div class="col-md-6">
            <!-- Enlace para gestionar productos -->
            <a href="{{ route('agricultor.gestionar-productos') }}" class="btn btn-primary w-100">Gestionar Mis Productos</a>
        </div>
        <div class="col-md-6">
            <!-- Enlace para ver pedidos recibidos -->
            <a href="{{ route('agricultor.pedidos-recibidos') }}" class="btn btn-secondary w-100">Ver Pedidos Recibidos</a>
        </div>
    </div>
</div>

<h1>Dashboard del Agricultor</h1>
<h2>Mis Productos</h2>
<ul>
    @foreach ($productos as $producto)
        <li>{{ $producto->nombre }} - {{ $producto->cantidad_disponible }} disponibles</li>
    @endforeach
</ul>
@endsection