<!-- filepath: c:\Users\User\prueba Final\test-app\resources\views\comprador\pagar-pedidos.blade.php -->
@extends('layouts.app')

@section('title', 'Pagar Pedidos')

@section('content')
<div class="container mt-5">
    <h1 class="text-center fw-bold text-success mb-4">Pagar Pedidos</h1>
    <p class="text-center">Total a pagar: <span class="fw-bold text-success">${{ $total }}</span></p>

    <div class="card shadow p-4">
        <form id="paymentForm" action="{{ route('comprador.confirmar-pago') }}" method="POST" class="mt-4" novalidate>
            @csrf
            <!-- Número de Tarjeta -->
            <div class="mb-3">
                <label for="card_number" class="form-label">Número de Tarjeta</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                    <input 
                        type="text" 
                        name="card_number" 
                        id="card_number" 
                        class="form-control" 
                        placeholder="1234 5678 9012 3456" 
                        required 
                        pattern="\d{16}" 
                        maxlength="16"
                        title="El número de tarjeta debe contener exactamente 16 dígitos.">
                </div>
            </div>

            <!-- Nombre en la Tarjeta -->
            <div class="mb-3">
                <label for="card_name" class="form-label">Nombre en la Tarjeta</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input 
                        type="text" 
                        name="card_name" 
                        id="card_name" 
                        class="form-control" 
                        placeholder="Juan Pérez" 
                        required 
                        maxlength="255"
                        pattern="[a-zA-Z\s]+" 
                        title="El nombre debe contener solo letras y espacios.">
                </div>
            </div>

            <!-- Fecha de Expiración y CVV -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="expiry_date" class="form-label">Fecha de Expiración</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        <input 
                            type="text" 
                            name="expiry_date" 
                            id="expiry_date" 
                            class="form-control" 
                            placeholder="MM/AA" 
                            required 
                            pattern="^(0[1-9]|1[0-2])\\/([0-9]{2})$" 
                            title="La fecha de expiración debe estar en el formato MM/AA.">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cvv" class="form-label">CVV</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input 
                            type="text" 
                            name="cvv" 
                            id="cvv" 
                            class="form-control" 
                            placeholder="123" 
                            required 
                            pattern="\d{3}" 
                            maxlength="3"
                            title="El CVV debe contener exactamente 3 dígitos.">
                    </div>
                </div>
            </div>

            <!-- Botón de Confirmar Pago -->
            <button type="submit" class="btn btn-success w-100 btn-lg mt-3">Confirmar Pago</button>
        </form>
    </div>
</div>

<!-- Script para validar el formulario -->
<script>
    document.getElementById('paymentForm').addEventListener('submit', function(event) {
        const form = event.target;
        const inputs = form.querySelectorAll('input[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            event.preventDefault();
            alert('Por favor, complete todos los campos obligatorios.');
        }
    });
</script>

<style>
    .is-invalid {
        border-color: #dc3545;
    }
</style>
@endsection