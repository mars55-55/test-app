<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 📝 REGISTRO de usuario
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Crear el usuario con el rol por defecto de "comprador"
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role_id' => 2, // Asigna el rol de "comprador" por defecto
        ]);

        return redirect('/login')->with('success', 'Usuario registrado con éxito.');
    }

    // 🔑 LOGIN de usuario
    public function login(Request $request)
    {
        // Validar las credenciales
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Obtener el usuario autenticado
            $user = Auth::user();

            // Redirigir según el rol del usuario
            if ($user->role->name === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role->name === 'agricultor') {
                return redirect()->route('agricultor.dashboard');
            } elseif ($user->role->name === 'comprador') {
                return redirect()->route('comprador.dashboard');
            }

            // Si el rol no coincide con ninguno, redirigir al inicio
            return redirect()->route('home');
        }

        // Si las credenciales no son válidas
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // 🚪 LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
