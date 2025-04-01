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

    public function comprar(Request $request, $id)
    {
        // Valida la cantidad ingresada
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        // Busca el producto por ID
        $producto = Producto::findOrFail($id);

        // Verifica si hay suficiente cantidad disponible
        if ($producto->cantidad_disponible < $request->cantidad) {
            return redirect()->back()->with('error', 'La cantidad solicitada no está disponible.');
        }

        // Crea un nuevo pedido
        $pedido = Pedido::create([
            'usuario_id' => auth()->id(), // ID del comprador autenticado
            'total' => $producto->precio * $request->cantidad, // Total basado en la cantidad
            'estado' => 'pendiente', // Estado inicial del pedido
        ]);

        // Asocia el producto al pedido en la tabla pedido_producto
        $pedido->productos()->attach($producto->id, ['cantidad' => $request->cantidad]);

        // Reduce la cantidad disponible del producto
        $producto->cantidad_disponible -= $request->cantidad;
        $producto->save();

        // Redirige al dashboard del comprador con un mensaje de éxito
        return redirect()->route('comprador.dashboard')->with('success', 'Producto agregado a tus pedidos.');
    }

    public function cancelarPedido($id)
    {
        // Busca el pedido por ID y verifica que pertenezca al usuario autenticado
        $pedido = Pedido::where('id', $id)->where('usuario_id', Auth::id())->firstOrFail();

        // Cambia el estado del pedido a "cancelado"
        $pedido->estado = 'cancelado';
        $pedido->save();

        // Opcional: Restaurar la cantidad de los productos al inventario
        foreach ($pedido->productos as $producto) {
            $producto->cantidad_disponible += $producto->pivot->cantidad;
            $producto->save();
        }

        // Redirige al dashboard con un mensaje de éxito
        return redirect()->route('comprador.dashboard')->with('success', 'El pedido ha sido cancelado.');
    }

    public function pagarPedidos(Request $request)
    {
        // Obtiene los pedidos pendientes del usuario autenticado
        $pedidos = Pedido::where('usuario_id', Auth::id())
            ->where('estado', 'pendiente')
            ->get();

        if ($pedidos->isEmpty()) {
            return redirect()->route('comprador.dashboard')->with('error', 'No tienes pedidos pendientes para pagar.');
        }

        // Calcula el total de los pedidos pendientes
        $total = $pedidos->sum('total');

        // Redirige a la vista de pago con el total
        return view('comprador.pagar-pedidos', compact('total'));
    }

    public function confirmarPago(Request $request)
    {
        // Valida los datos de la tarjeta
        $request->validate([
            'card_number' => 'required|digits:16',
            'card_name' => 'required|string|max:255',
            'expiry_date' => 'required',
            'cvv' => 'required|digits:3',
        ]);

        // Obtiene los pedidos pendientes del usuario autenticado
        $pedidos = Pedido::where('usuario_id', Auth::id())
            ->where('estado', 'pendiente')
            ->get();

        if ($pedidos->isEmpty()) {
            return redirect()->route('comprador.dashboard')->with('error', 'No tienes pedidos pendientes para pagar.');
        }

        // Simula el procesamiento del pago
        $total = $pedidos->sum('total');

        // Actualiza el estado de los pedidos a "pagado"
        foreach ($pedidos as $pedido) {
            $pedido->estado = 'pagado';
            $pedido->save();
        }

        // Redirige al dashboard con un mensaje de éxito
        return redirect()->route('comprador.dashboard')->with('success', 'Pago realizado con éxito. Total pagado: $' . $total);
    }
}
