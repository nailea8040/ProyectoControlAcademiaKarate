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
        
        $usuario = Usuario::where('correo', $correo)->first(); 

        // Verificar si el usuario existe y si la contraseña es correcta
      
        if ($usuario && Hash::check($pass_ingresada, $usuario->pass)) { 
            
            Auth::login($usuario); 
            
            $request->session()->regenerate();

            return redirect()->intended('/principal')
                             ->with('success', '¡Bienvenido ' . $usuario->nombre . '!');
        }

        // Autenticación fallida
        Log::warning('Intento de login fallido para el correo: ' . $correo);

        return back() 
            ->withInput($request->only('correo')) 
            ->withErrors(['login_fallido' => 'Credenciales incorrectas. Verifique su correo y contraseña.']);
    }

    public function logout(Request $request)
    {
  
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
                ->with('status', 'Ha cerrado la sesión con éxito.');
    }
}