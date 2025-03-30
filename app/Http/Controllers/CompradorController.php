<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class CompradorController extends Controller
{
    public function dashboard()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar si el usuario tiene el rol 'comprador'
        if (!$user || $user->role->name !== 'comprador') {
            return redirect('/acceso-denegado')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        // Obtener los pedidos del comprador
        $pedidos = Pedido::where('usuario_id', $user->id)->get();

        // Obtener los productos disponibles
        $productos = Producto::all();

        return view('comprador.dashboard', compact('pedidos', 'productos'));
    }

    public function realizarPedido()
    {
        $user = Auth::user();

        if (!$user || $user->role->name !== 'comprador') {
            return redirect('/acceso-denegado')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        // Aquí puedes agregar lógica para realizar un pedido
        return view('comprador.realizar-pedido');
    }
}
