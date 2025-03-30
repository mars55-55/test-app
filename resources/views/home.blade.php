@extends('layouts.app')

@section('title', 'Inicio - AgriConnect')

@section('content')
<!-- Banner -->
<div class="banner">
    <div class="banner-text text-center">
        <h1>Bienvenido a AgriConnect</h1>
        <p>Conectamos agricultores y compradores en un solo lugar.</p>
        <a href="{{ route('register') }}" class="btn btn-success btn-lg">Regístrate Ahora</a>
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Inicia Sesión</a>
    </div>
</div>

<!-- Sección de Información -->
<div class="container mt-5">
    <div class="row text-center">
        <div class="col-md-4">
            <h3>Misión</h3>
            <p>
                Facilitar la conexión entre agricultores y compradores, promoviendo el comercio justo 
                y el desarrollo sostenible en el sector agrícola.
            </p>
        </div>
        <div class="col-md-4">
            <h3>Visión</h3>
            <p>
                Ser la plataforma líder en la transformación digital del sector agrícola, empoderando 
                a los agricultores y mejorando el acceso a productos frescos para los compradores.
            </p>
        </div>
        <div class="col-md-4">
            <h3>Valores</h3>
            <p>
                Compromiso, sostenibilidad, innovación y confianza son los pilares que nos guían 
                para transformar el sector agrícola.
            </p>
        </div>
    </div>
</div>

<!-- Sección de Beneficios -->
<div class="container mt-5">
    <h2 class="text-center mb-4">¿Por qué elegir AgriConnect?</h2>
    <div class="row">
        <div class="col-md-4 text-center">
            <img src="/images/fresh-produce.png" alt="Productos Frescos" class="benefit-icon">
            <h4>Productos Frescos</h4>
            <p>Accede a productos directamente del agricultor, garantizando frescura y calidad.</p>
        </div>
        <div class="col-md-4 text-center">
            <img src="/images/fair-trade.png" alt="Comercio Justo" class="benefit-icon">
            <h4>Comercio Justo</h4>
            <p>Apoya a los agricultores con precios justos y fomenta el desarrollo sostenible.</p>
        </div>
        <div class="col-md-4 text-center">
            <img src="/images/technology.png" alt="Tecnología" class="benefit-icon">
            <h4>Tecnología</h4>
            <p>Utilizamos tecnología avanzada para conectar agricultores y compradores de manera eficiente.</p>
        </div>
    </div>
</div>
@endsection