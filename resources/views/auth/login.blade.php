@extends('layouts.app')

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        <strong>Error:</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <strong>¡Éxito!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@section('title', 'Iniciar Sesión')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header text-center bg-success text-white">
                <h3 class="fw-bold">Iniciar Sesión</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold text-success">Correo</label>
                        <input type="email" name="email" class="form-control border-success" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold text-success">Contraseña</label>
                        <input type="password" name="password" class="form-control border-success" id="password" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100 rounded-pill">Iniciar Sesión</button>
                </form>

                {{-- Mostrar errores de autenticación --}}
                @if ($errors->any())
                    <div class="text-danger mt-3">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
