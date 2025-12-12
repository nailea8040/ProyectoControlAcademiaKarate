<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    public function index()
    {
        //  Obtener todos los usuarios de la BD
        $usuario = DB::connection('mysql')
            ->table('usuario')
            ->where('rol', '!=', 'administrador')
            ->get();
        
        // Mostrar la vista, pasándole la lista de usuarios
        return view('usuariosViews.usuarios', ['usuarios' => $usuario]);
    }

    // Método para insetar usuarios
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'apaterno' => 'required|string',
            'amaterno' => 'required|string',
            'fecha_naci' => 'required|date',
            'tel' => 'required|string|max:10',
            'correo' => 'required|email|unique:usuario,correo',
            'pass' => 'required|min:6',
            'rol' => 'required|in:administrador,sensei,tutor,alumno',
            'fecha_registro' => 'required|date',
        ]);

        try {
            DB::connection('mysql')
                ->table('usuario')
                ->insert([
                    'nombre' => $validated['nombre'],
                    'apaterno' => $validated['apaterno'],
                    'amaterno' => $validated['amaterno'],
                    'fecha_naci' => $validated['fecha_naci'],
                    'tel' => $validated['tel'],
                    'correo' => $validated['correo'],
                    'pass' => Hash::make($validated['pass']), 
                    'rol' => $validated['rol'],
                    'fecha_registro' => $validated['fecha_registro'],
                ]);

        
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
    
    public function VerLogin(){
   
        return view('login');
    }
    public function show(){}
    public function edit($id){
     // 1. Buscar el usuario por su clave primaria
        $usuario = DB::connection('mysql')
            ->table('usuario')
            ->where('id_usuario', $id) 
            ->first();

        if (!$usuario) {
            return redirect()->route('usuarios.index')
                             ->with('sessionInsertado', 'false')
                             ->with('mensaje', 'Usuario no encontrado para edición.');
        }

        
        return view('usuariosViews.editarUsu', compact('usuario'));
    }
    public function update(Request $request, $id){
        // 1. Validar los datos
        $validated = $request->validate([
            'nombre' => 'required|string',
            'apaterno' => 'required|string',
            'amaterno' => 'required|string',
            'fecha_naci' => 'required|date',
            'tel' => 'required|string|max:20',
            // El correo debe ser único, IGNORANDO el correo del usuario actual por su ID
            'correo' => 'required|email|unique:usuario,correo,'.$id.',id_usuario', 
            'rol' => 'required|in:administrador,sensei,tutor,alumno',
            'pass' => 'nullable|min:6', 
        ]);

        try {
            $dataToUpdate = [
                'nombre' => $validated['nombre'],
                'apaterno' => $validated['apaterno'],
                'amaterno' => $validated['amaterno'],
                'fecha_naci' => $validated['fecha_naci'],
                'tel' => $validated['tel'],
                'correo' => $validated['correo'],
                'rol' => $validated['rol'],
            ];

            // Manejar la contraseña solo si el campo 'pass' fue llenado
            if (!empty($validated['pass'])) {
                $dataToUpdate['pass'] = Hash::make($validated['pass']);
            }

            // Ejecutar la actualización en la BD
            $updated = DB::connection('mysql')
                ->table('usuario')
                ->where('id_usuario', $id)
                ->update($dataToUpdate);

            //  Redirigir al listado con un mensaje de éxito
            return redirect()
                ->route('usuarios.index')
                ->with('sessionInsertado', 'true')
                ->with('mensaje', '¡Usuario con ID ' . $id . ' actualizado con éxito!');

        } catch (\Exception $e) {
            Log::error("Error al actualizar usuario ID $id: " . $e->getMessage());
            // Si falla, regresa al formulario de edición con un error
            return redirect()
                ->route('editarUsu', $id) // O 'usuarios.edit' si usas la convención RESTful
                ->withInput()
                ->with('sessionInsertado', 'false')
                ->with('mensaje', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    
    }
    public function destroy($id){}
    public function confirmMail($correo){} // Mantener si es necesaria

}