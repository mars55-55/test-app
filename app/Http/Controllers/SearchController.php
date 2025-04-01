<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Pedido;

class SearchController extends Controller
{
    public function buscar(Request $request)
    {
        $query = $request->input('query');
        $user = auth()->user();

        // Verifica el rol del usuario
        if ($user->role->name === 'comprador') {
            // Buscar productos disponibles para el comprador
            $resultados = Producto::where('nombre', 'LIKE', "%$query%")
                ->orWhere('descripcion', 'LIKE', "%$query%")
                ->get();
        } elseif ($user->role->name === 'agricultor') {
            // Buscar productos del agricultor autenticado
            $resultados = Producto::where('agricultor_id', $user->id)
                ->where(function ($q) use ($query) {
                    $q->where('nombre', 'LIKE', "%$query%")
                      ->orWhere('descripcion', 'LIKE', "%$query%");
                })
                ->get();
        } elseif ($user->role->name === 'admin') {
            // Buscar pedidos para el administrador
            $resultados = Pedido::where('id', 'LIKE', "%$query%")
                ->orWhereHas('usuario', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%");
                })
                ->get();
        } else {
            $resultados = collect(); // Sin resultados para roles desconocidos
        }

        // Retorna la vista con los resultados
        return view('resultados', compact('resultados', 'query'));
    }
}
