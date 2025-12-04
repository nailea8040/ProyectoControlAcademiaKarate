<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TutorController extends Controller
{
    // DEFINICIONES CONSISTENTES
    private $userTable = 'usuario'; // Tabla de Usuarios (singular)
    private $userIdColumn = 'id';    // Columna clave en la tabla 'usuario'

    /**
     * Muestra la lista de tutores registrados y el formulario.
     */
    public function index()
    {
        try {
            // 1. Obtener la lista de TUTORES ya registrados con sus detalles
            $tutores_registrados = DB::table('tutor') // Asumimos una tabla 'tutor'
                ->join($this->userTable, 'tutor.id_tutor', '=', "{$this->userTable}.{$this->userIdColumn}")
                ->select(
                    'tutor.*', // Selecciona los campos específicos de la tabla 'tutor'
                    // Obtiene el nombre completo del usuario asociado
                    DB::raw("CONCAT({$this->userTable}.nombre, ' ', {$this->userTable}.apaterno, ' ', {$this->userTable}.amaterno) AS nombre_completo")
                )
                ->get();
                
            // 2. Obtener los usuarios con rol 'tutor' para el dropdown (aquellos que aún no han sido asignados o necesitamos listar)
            $usuarios_tutor = DB::table($this->userTable)
                ->where('rol', 'tutor')
                ->select("{$this->userIdColumn} AS id_tutor", DB::raw("CONCAT(nombre, ' ', apaterno) AS nombre_completo"))
                ->get();

            return view('usuariosViews.tutor', compact('tutores_registrados', 'usuarios_tutor'));

        } catch (\Exception $e) {
            Log::error('Error en TutorController@index: ' . $e->getMessage());
            return view('usuariosViews.tutor', ['tutores_registrados' => [], 'usuarios_tutor' => []])
                   ->with('mensaje', 'Error al cargar datos: ' . $e->getMessage());
        }
    }

    /**
     * Procesa la inserción de un nuevo tutor con sus detalles.
     */
    public function store(Request $request)
    {
        // Asumimos que los campos adicionales del formulario son: 'ocupacion' y 'relacion_estudiante'
        $validated = $request->validate([
            'id_tutor' => "required|exists:{$this->userTable},{$this->userIdColumn}|unique:tutor,id_tutor", 
            'ocupacion' => 'required|string|max:100',
            'relacion_estudiante' => 'required|string|max:100',
        ]);
        
        try {
            DB::table('tutor')->insert([ // Insertamos en la tabla 'tutor'
                'id_tutor' => $validated['id_tutor'],
                'ocupacion' => $validated['ocupacion'],
                'relacion_estudiante' => $validated['relacion_estudiante'],
                'fecha_registro' => now(), // Asumiendo que esta tabla también tiene una columna de fecha
            ]);

            return redirect()->route('tutor.index')->with('sessionInsertado', 'true')->with('mensaje', 'Detalles del tutor registrados con éxito.');
        } catch (\Exception $e) {
            Log::error('Error en TutorController@store: ' . $e->getMessage());
            return redirect()->route('tutor.index')->with('sessionInsertado', 'false')->with('mensaje', 'Error al registrar detalles del tutor: ' . $e->getMessage());
        }
    }
    
    // ... (otros métodos) ...
}