@extends('layouts.app')

@section('title', 'Registro de Usuario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header text-center bg-success text-white">
                <h3 class="fw-bold">Registro de Usuario</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register.api') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold text-success">Nombre</label>
                        <input type="text" name="name" class="form-control border-success" id="name" placeholder="Ingresa tu nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold text-success">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control border-success" id="email" placeholder="Ingresa tu correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold text-success">Contraseña</label>
                        <input type="password" name="password" class="form-control border-success" id="password" placeholder="Ingresa tu contraseña" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-bold text-success">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control border-success" id="password_confirmation" placeholder="Confirma tu contraseña" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100 rounded-pill shadow-sm">Registrarse</button>
                </form>

                {{-- Mostrar errores de validación --}}
                @if ($errors->any())
                    <div class="text-danger mt-2">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-success fw-bold">¿Ya tienes una cuenta? Inicia sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection