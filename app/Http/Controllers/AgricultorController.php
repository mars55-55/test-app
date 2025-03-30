<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class AgricultorController extends Controller
{
    public function dashboard()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar si el usuario tiene el rol 'agricultor'
        if (!$user || $user->role->name !== 'agricultor') {
            return redirect('/acceso-denegado')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        // Obtener los productos del agricultor
        $productos = Producto::where('agricultor_id', $user->id)->get();

        return view('agricultor.dashboard', compact('productos'));
    }

    public function gestionarProductos()
    {
        $user = Auth::user();

        if (!$user || $user->role->name !== 'agricultor') {
            return redirect('/acceso-denegado')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        $productos = Producto::where('agricultor_id', $user->id)->get();

        return view('agricultor.gestionar-productos', compact('productos'));
    }

    public function pedidosRecibidos()
    {
        $user = Auth::user();

        if (!$user || $user->role->name !== 'agricultor') {
            return redirect('/acceso-denegado')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        // Aquí puedes agregar lógica para obtener los pedidos relacionados con los productos del agricultor
        $pedidos = []; // Ejemplo vacío

        return view('agricultor.pedidos-recibidos', compact('pedidos'));
    }
}
