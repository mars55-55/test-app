// filepath: c:\Users\marti\test-app\resources\views\auth\passwords\email.blade.php
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Recuperar contraseña</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button class="btn btn-primary">Enviar enlace de recuperación</button>
    </form>
</div>
@endsection