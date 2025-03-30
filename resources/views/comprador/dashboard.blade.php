@extends('layouts.app')

@section('content')
<h1>Dashboard del Comprador</h1>
<h2>Mis Pedidos</h2>
<ul>
    @foreach ($pedidos as $pedido)
        <li>Pedido #{{ $pedido->id }} - Total: {{ $pedido->total }} - Estado: {{ $pedido->estado }}</li>
    @endforeach
</ul>

<h2>Productos Disponibles</h2>
<ul>
    @foreach ($productos as $producto)
        <li>{{ $producto->nombre }} - Precio: {{ $producto->precio }}</li>
    @endforeach
</ul>
@endsection