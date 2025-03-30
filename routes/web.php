<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgricultorController;
use App\Http\Controllers\CompradorController;
use App\Http\Middleware\Authenticate;


Route::get('/login', function () {
    dump(auth()->user()->name ?? 'No hay usuario autenticado');
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/productos', [AdminController::class, 'productos'])->name('admin.productos');
    Route::get('/pedidos', [AdminController::class, 'pedidos'])->name('admin.pedidos');
})->middleware(Authenticate::class);

// Rutas para Agricultores
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':agricultor'])->group(function () {
    Route::get('/agricultor/dashboard', [AgricultorController::class, 'dashboard'])->name('agricultor.dashboard');
    Route::get('/agricultor/gestionar-productos', [AgricultorController::class, 'gestionarProductos'])->name('agricultor.gestionar-productos');
    Route::get('/agricultor/pedidos-recibidos', [AgricultorController::class, 'pedidosRecibidos'])->name('agricultor.pedidos-recibidos');
});

// Rutas para Compradores
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':comprador'])->group(function () {
    Route::get('/comprador/dashboard', [CompradorController::class, 'dashboard'])->name('comprador.dashboard');
    Route::get('/comprador/realizar-pedido', [CompradorController::class, 'realizarPedido'])->name('comprador.realizar-pedido');
});
