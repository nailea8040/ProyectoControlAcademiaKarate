<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use App\Models\Usuario;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login'); 
    }

    //
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contra' => 'required|string',
        ]);

        $correo = $request->input('correo');
        $pass_ingresada = $request->input('contra');
        
        $usuario = Usuario::where('correo', $correo)->first(); 

      //
      
        if ($usuario && Hash::check($pass_ingresada, $usuario->pass)) { 
            if ($usuario->estado !== true) {
            Log::warning('Intento de login con cuenta inactiva para el correo: ' . $correo);

            return back() 
                ->withInput($request->only('correo')) 
                ->withErrors(['cuenta_inactiva' => 'Su cuenta está inactiva']);
        }
            
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