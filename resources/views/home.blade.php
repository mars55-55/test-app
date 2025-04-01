@extends('layouts.app')

@section('title', 'Inicio - AgriConnect')

@section('content')
<!-- Banner -->
<div class="banner d-flex align-items-center justify-content-center text-center">
    <div class="banner-text">
        <h1 class="display-4 fw-bold text-white text-shadow">Bienvenido a AgriConnect</h1>
        <p class="lead text-white text-shadow">Conectamos agricultores y compradores en un solo lugar.</p>
        <a href="{{ route('register') }}" class="btn btn-success btn-lg me-2 rounded-pill shadow-lg px-4 py-2 custom-btn">Regístrate Ahora</a>
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg rounded-pill shadow-lg px-4 py-2 custom-btn">Inicia Sesión</a>
    </div>
</div>

<!-- Sección de Información -->
<div class="container mt-5">
    <h2 class="text-center fw-bold text-success mb-4">Nuestra Filosofía</h2>
    <div class="row text-center">
        <div class="col-md-4">
            <div class="info-card p-4 shadow-sm rounded">
                <h3 class="fw-bold text-success">Misión</h3>
                <p>
                    Facilitar la conexión entre agricultores y compradores, promoviendo el comercio justo 
                    y el desarrollo sostenible en el sector agrícola.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card p-4 shadow-sm rounded">
                <h3 class="fw-bold text-success">Visión</h3>
                <p>
                    Ser la plataforma líder en la transformación digital del sector agrícola, empoderando 
                    a los agricultores y mejorando el acceso a productos frescos para los compradores.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card p-4 shadow-sm rounded">
                <h3 class="fw-bold text-success">Valores</h3>
                <p>
                    Compromiso, sostenibilidad, innovación y confianza son los pilares que nos guían 
                    para transformar el sector agrícola.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Beneficios -->
<div class="container mt-5">
    <h2 class="text-center fw-bold text-success mb-4">¿Por qué elegir AgriConnect?</h2>
    <div class="row">
        <div class="col-md-4 text-center">
            <div class="benefit-card p-4 shadow-sm rounded">
                <img src="/images/fresh-produce.png" alt="Productos Frescos" class="benefit-icon mb-3">
                <h4 class="fw-bold">Productos Frescos</h4>
                <p>Accede a productos directamente del agricultor, garantizando frescura y calidad.</p>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div class="benefit-card p-4 shadow-sm rounded">
                <img src="/images/fair-trade.png" alt="Comercio Justo" class="benefit-icon mb-3">
                <h4 class="fw-bold">Comercio Justo</h4>
                <p>Apoya a los agricultores con precios justos y fomenta el desarrollo sostenible.</p>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div class="benefit-card p-4 shadow-sm rounded">
                <img src="/images/technology.png" alt="Tecnología" class="benefit-icon mb-3">
                <h4 class="fw-bold">Tecnología</h4>
                <p>Utilizamos tecnología avanzada para conectar agricultores y compradores de manera eficiente.</p>
            </div>
        </div>
    </div>
</div>
@endsection