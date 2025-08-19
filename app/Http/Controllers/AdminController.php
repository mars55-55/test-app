<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Producto;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

    public function editarUsuario($id)
    {
        $usuario = User::find($id);

        if (!$usuario) {
            abort(404, 'Usuario no encontrado');
        }

        // Obtener todos los roles
        $roles = \App\Models\Role::all();

        return view('admin.usuario-editar', compact('usuario', 'roles'));
    }

    public function eliminarUsuario($id)
    {
        $usuario = User::find($id);

        if (!$usuario) {
            abort(404, 'Usuario no encontrado');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios')->with('success', 'Usuario eliminado correctamente.');
    }

    public function actualizarUsuario(Request $request, $id)
    {
        $usuario = User::find($id);

        if (!$usuario) {
            abort(404, 'Usuario no encontrado');
        }

        $usuario->update($request->only(['name', 'email', 'role_id']));

        return redirect()->route('admin.usuarios')->with('success', 'Usuario actualizado correctamente.');
    }

    public function pedidos()
    {
        // Obtiene todos los pedidos con sus relaciones
        $pedidos = Pedido::with('usuario', 'productos')->get();

        // Retorna la vista con los pedidos
        return view('admin.pedidos', compact('pedidos'));
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|in:1,2',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('admin.usuarios')->with('registro_exito', 'Usuario registrado correctamente.');
    }
}
