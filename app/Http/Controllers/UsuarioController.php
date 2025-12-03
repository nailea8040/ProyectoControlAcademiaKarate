<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    // Eliminamos 'create' porque 'index' hará todo
    
    // Método UNIFICADO para LISTAR (GET /usuarios)
    public function index()
    {
        // 1. Obtener todos los usuarios de la BD
        $usuarios = DB::connection('mysql')
            ->table('usuarios')
            ->get();
        
        // 2. Mostrar la vista, pasándole la lista de usuarios
        return view('usuariosViews.usuarios', ['usuarios' => $usuarios]);
    }

    // Método para INSERTAR (POST /usuarios)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'apaterno' => 'required|string',
            'amaterno' => 'required|string',
            'fecha_naci' => 'required|date',
            'tel' => 'required|string|max:10',
            'correo' => 'required|email|unique:usuarios,correo', // Corregido el nombre de la tabla
            'pass' => 'required|min:6',
            'rol' => 'required|in:administrador,sensei,tutor,alumno',
            'fecha_registro' => 'required|date',
        ]);

        try {
            DB::connection('mysql')
                ->table('usuarios')
                ->insert([
                    'nombre' => $validated['nombre'],
                    'apaterno' => $validated['apaterno'],
                    'amaterno' => $validated['amaterno'],
                    'fecha_naci' => $validated['fecha_naci'],
                    'tel' => $validated['tel'],
                    'correo' => $validated['correo'],
                    'pass' => Hash::make($validated['pass']), // Cifrado de la contraseña
                    'rol' => $validated['rol'],
                    'fecha_registro' => $validated['fecha_registro'],
                ]);

            // Redirigimos a la ruta INDEX (/usuarios) para ver la tabla actualizada
            // Usamos 'with' para mostrar un mensaje de éxito
            return redirect()
                ->route('usuarios.index') // Redirige a GET /usuarios
                ->with('sessionInsertado', 'true')
                ->with('mensaje', '¡Usuario registrado con éxito!');

        } catch (\Exception $e) {
            Log::error('Error al registrar usuario: ' . $e->getMessage());
            
            return redirect()
                ->route('usuarios.index')
                ->with('sessionInsertado', 'false')
                ->with('mensaje', 'Hubo un error en el servidor al intentar registrar el usuario.');
        }
    }
    
    // Dejamos los otros métodos vacíos o con la implementación necesaria (como VerLogin)
    public function VerLogin(){
        // Lógica para ver el formulario de login, si es diferente al de registro
    }
    public function show(){}
    public function edit($id){}
    public function update($id){}
    public function destroy($id){}
    public function confirmMail($correo){} // Mantener si es necesaria

}