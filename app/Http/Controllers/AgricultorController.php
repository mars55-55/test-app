<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

    public function crearProducto()
    {
        return view('agricultor.crear-producto');
    }

    public function guardarProducto(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'cantidad_disponible' => 'required|integer|min:1',
        ]);

        Producto::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'precio' => $validated['precio'],
            'cantidad_disponible' => $validated['cantidad_disponible'],
            'agricultor_id' => $user->id,
        ]);

        return redirect()->route('agricultor.gestionar-productos')->with('success', 'Producto creado con éxito.');
    }

    public function pedidosRecibidos()
    {
        $user = Auth::user();

        if (!$user || $user->role->name !== 'agricultor') {
            return redirect('/acceso-denegado')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        // Obtener los pedidos relacionados con los productos del agricultor
        $pedidos = Pedido::whereHas('productos', function ($query) use ($user) {
            $query->where('agricultor_id', $user->id);
        })->get();

        return view('agricultor.pedidos-recibidos', compact('pedidos'));
    }
}
