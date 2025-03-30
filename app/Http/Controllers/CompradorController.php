<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

    public function verProductos()
    {
        $productos = Producto::where('cantidad_disponible', '>', 0)->get();

        return view('comprador.ver-productos', compact('productos'));
    }

    public function agregarAlCarrito(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad']++;
        } else {
            $carrito[$id] = [
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => 1,
            ];
        }

        session()->put('carrito', $carrito);

        return redirect()->route('comprador.ver-productos')->with('success', 'Producto agregado al carrito.');
    }

    public function verCarrito()
    {
        $carrito = session()->get('carrito', []);

        return view('comprador.ver-carrito', compact('carrito'));
    }

    public function realizarPedido(Request $request)
    {
        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('comprador.ver-carrito')->with('error', 'El carrito está vacío.');
        }

        // Crear el pedido
        $pedido = Pedido::create([
            'usuario_id' => Auth::id(),
            'total' => array_sum(array_map(function ($producto) {
                return $producto['precio'] * $producto['cantidad'];
            }, $carrito)),
        ]);

        // Asociar los productos al pedido
        foreach ($carrito as $id => $producto) {
            $pedido->productos()->attach($id, ['cantidad' => $producto['cantidad']]);
        }

        // Limpiar el carrito
        session()->forget('carrito');

        // Redirigir a la vista de confirmación
        return view('comprador.realizar-pedido');
    }
}
