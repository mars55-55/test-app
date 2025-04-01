<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgricultorController;
use App\Http\Controllers\CompradorController;
use App\Http\Controllers\ProductoController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home'); // Carga la vista home.blade.php
})->name('home');   

Route::get('/login', function () {
    dump(auth()->user()->name ?? 'No hay usuario autenticado');
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/register', [AuthController::class, 'register'])->name('register.api');

// Rutas para Administradores
Route::prefix('admin')->middleware(Authenticate::class)->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/admin/usuarios/{id}/editar', [AdminController::class, 'editarUsuario'])->name('admin.usuarios.editar');
    Route::delete('/admin/usuarios/{id}', [AdminController::class, 'eliminarUsuario'])->name('admin.usuarios.eliminar');
    Route::put('/admin/usuarios/{id}', [AdminController::class, 'actualizarUsuario'])->name('admin.usuarios.actualizar');
});

// Rutas para Agricultores
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':agricultor'])->group(function () {
    Route::get('/agricultor/dashboard', [AgricultorController::class, 'dashboard'])->name('agricultor.dashboard');
    Route::get('/agricultor/gestionar-productos', [AgricultorController::class, 'gestionarProductos'])->name('agricultor.gestionar-productos');
    Route::get('/agricultor/pedidos-recibidos', [AgricultorController::class, 'pedidosRecibidos'])->name('agricultor.pedidos-recibidos');
    Route::get('/agricultor/crear-producto', [AgricultorController::class, 'crearProducto'])->name('agricultor.crear-producto');
    Route::post('/agricultor/guardar-producto', [AgricultorController::class, 'guardarProducto'])->name('agricultor.guardar-producto');
    Route::get('/agricultor/editar-producto/{id}', [AgricultorController::class, 'editarProducto'])->name('agricultor.editar-producto');
    Route::put('/agricultor/actualizar-producto/{id}', [AgricultorController::class, 'actualizarProducto'])->name('agricultor.actualizar-producto');
    Route::delete('/agricultor/eliminar-producto/{id}', [AgricultorController::class, 'eliminarProducto'])->name('agricultor.eliminar-producto');
    Route::get('/agricultor/pedidos/{id}', [AgricultorController::class, 'detallesPedido'])->name('agricultor.pedidos.detalles');
});

// Rutas para Compradores
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':comprador'])->group(function () {
    Route::get('/comprador/dashboard', [CompradorController::class, 'dashboard'])->name('comprador.dashboard');
    Route::get('/comprador/realizar-pedido', [CompradorController::class, 'realizarPedido'])->name('comprador.realizar-pedido');
    Route::get('/comprador/ver-productos', [CompradorController::class, 'verProductos'])->name('comprador.ver-productos');
    Route::post('/comprador/agregar-al-carrito/{id}', [CompradorController::class, 'agregarAlCarrito'])->name('comprador.agregar-al-carrito');
    Route::get('/comprador/ver-carrito', [CompradorController::class, 'verCarrito'])->name('comprador.ver-carrito');
    Route::post('/comprador/realizar-pedido', [CompradorController::class, 'realizarPedido'])->name('comprador.realizar-pedido');
});
