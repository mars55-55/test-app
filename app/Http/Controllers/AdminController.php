<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Producto;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar si el usuario tiene el rol 'admin'
        if (!$user || $user->role->name !== 'admin') {
            return redirect('/acceso-denegado')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return view('admin.dashboard');
    }

    public function usuarios()
    {
        $usuarios = User::all();
        return view('admin.usuarios', compact('usuarios'));
    }

    public function productos()
    {
        $productos = Producto::all();
        return view('admin.productos', compact('productos'));
    }

    public function pedidos()
    {
        $pedidos = Pedido::with('detalles')->get();
        return view('admin.pedidos', compact('pedidos'));
    }

    public function show($id)
    {
        $user = User::find($id); // Obtén el usuario por su ID

        if (!$user) {
            abort(404, 'Usuario no encontrado');
        }

        return view('user.profile', ['user' => $user]);
    }
}
