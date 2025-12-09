<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session; // Para manejar la sesión manualmente
use App\Models\Usuario;

class LoginController extends Controller
{
    /**
     * Muestra la vista del formulario de login.
     */
    public function showLoginForm()
    {
        // El nombre de tu vista de login (ej: 'login' o 'auth.login')
        return view('login'); 
    }

    /**
     * Procesa la solicitud POST para iniciar sesión.
     */
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contra' => 'required|string',
        ]);

        $correo = $request->input('correo');
        $pass_ingresada = $request->input('contra');
        
        // 2. Buscar el usuario (Usando el modelo Eloquent)
        // Usamos el modelo Usuario que ya configuraste.
        $usuario = Usuario::where('correo', $correo)->first(); 

        // 3. Verificar si el usuario existe y si la contraseña es correcta
        // Usamos la columna 'pass'
        if ($usuario && Hash::check($pass_ingresada, $usuario->pass)) { 
            
            // 🛑 4.1. ¡SOLUCIÓN! Autenticar al usuario de forma nativa en Laravel 🛑
            Auth::login($usuario); 
            
            // La sesión ya contiene el objeto Usuario completo, incluyendo el 'rol'.
            // Ya no necesitas Session::put('id_usuario'), Session::put('rol'), etc.
            
            $request->session()->regenerate();

            // 4.3. Redirigir al dashboard
            return redirect()->intended('/principal')
                             ->with('success', '¡Bienvenido ' . $usuario->nombre . '!');
        }

        // 5. Autenticación fallida
        Log::warning('Intento de login fallido para el correo: ' . $correo);

        return back() 
            ->withInput($request->only('correo')) 
            ->withErrors(['login_fallido' => 'Credenciales incorrectas. Verifique su correo y contraseña.']);
    }

    public function logout(Request $request)
    {
        // 🛑 ¡SOLUCIÓN! Usar Auth::logout() en lugar de Session::flush() 🛑
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
                ->with('status', 'Ha cerrado la sesión con éxito.');
    }
}