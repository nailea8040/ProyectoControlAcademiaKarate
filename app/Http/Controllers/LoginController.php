<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session; // Para manejar la sesión manualmente

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
        // 1. Validar los datos de entrada
        $request->validate([
            'correo' => 'required|email',
            'contra' => 'required|string',
        ]);

        $correo = $request->input('correo');
        $pass_ingresada = $request->input('contra');
        
        // 2. Buscar el usuario por correo en la tabla 'usuario'
        $usuario = DB::connection('mysql')
            ->table('usuario')
            ->where('correo', $correo)
            // Seleccionamos específicamente los campos que vamos a necesitar:
            // id_usuario, pass, rol, nombre, apaterno, amaterno
            ->select('id_usuario', 'pass', 'rol', 'nombre', 'apaterno', 'amaterno')
            ->first(); 

        // 3. Verificar si el usuario existe y si la contraseña es correcta
        if ($usuario && Hash::check($pass_ingresada, $usuario->pass)) {
            
            // 4. Autenticación exitosa
            
            // 4.1. Construir el nombre completo para mostrarlo en la interfaz (Opcional)
            $nombre_completo = trim($usuario->nombre . ' ' . $usuario->apaterno . ' ' . $usuario->amaterno);
            
            // 4.2. Crear la sesión utilizando id_usuario
            Session::put('authenticated', true);
            Session::put('id_usuario', $usuario->id_usuario); // <-- Usamos el ID correcto
            Session::put('rol', $usuario->rol);
            Session::put('nombre_completo', $nombre_completo); // Guardamos el nombre construido
            
            // 4.3. Regenerar la sesión por seguridad
            $request->session()->regenerate();

            // 4.4. Redirigir al dashboard
            return redirect()->intended('/usuarios')
                             ->with('success', '¡Bienvenido ' . $usuario->nombre . '!');
        }

        // 5. Autenticación fallida
        Log::warning('Intento de login fallido para el correo: ' . $correo);

        return back() 
            ->withInput($request->only('correo')) 
            ->withErrors(['login_fallido' => 'Credenciales incorrectas. Verifique su correo y contraseña.']);
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        Session::flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
                ->with('status', 'Ha cerrado la sesión con éxito.');
    }
}