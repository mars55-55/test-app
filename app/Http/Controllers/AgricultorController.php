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

    public function editarProducto($id)
    {
        $producto = Producto::findOrFail($id);
        return view('agricultor.editar-producto', compact('producto'));
    }

    public function actualizarProducto(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'cantidad_disponible' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update($validated);

        return redirect()->route('agricultor.gestionar-productos')->with('success', 'Producto actualizado con éxito.');
    }

    public function eliminarProducto($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('agricultor.gestionar-productos')->with('success', 'Producto eliminado con éxito.');
    }

    public function pedidosRecibidos()
    {
        // Obtener los pedidos relacionados con el agricultor autenticado
        $pedidos = Pedido::whereHas('productos', function ($query) {
            $query->where('agricultor_id', auth()->id());
        })->get();

        return view('agricultor.pedidos-recibidos', compact('pedidos'));
    }

    public function detallesPedido($id)
    {
        $pedido = Pedido::with('productos')->findOrFail($id);

        // Verificar que el pedido esté relacionado con el agricultor autenticado
        if (!$pedido->productos->where('agricultor_id', auth()->id())->count()) {
            abort(403, 'No tienes permiso para ver este pedido.');
        }

        return view('agricultor.pedido-detalles', compact('pedido'));
    }
}
