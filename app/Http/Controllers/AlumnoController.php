<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlumnoController extends Controller
{
    // Definimos las variables de la tabla principal para evitar errores
    private $userTable = 'usuarios';
    private $userIdColumn = 'id'; // Asumimos id como clave primaria
    
    // Muestra el listado de alumnos y el formulario de registro (GET /alumnos)
    public function index()
    {
        // 1. Obtener los alumnos registrados con sus nombres y el nombre de su tutor.
        $alumnos_registrados = DB::table('alumno')
            // JOIN usando la columna 'id'
            ->join("{$this->userTable} as a", 'alumno.id_alumno', '=', "a.{$this->userIdColumn}")
            ->join("{$this->userTable} as t", 'alumno.id_tutor', '=', "t.{$this->userIdColumn}")
            ->select(
                'alumno.*',
                DB::raw("CONCAT(a.nombre, ' ', a.apa) AS nombre_alumno"),
                DB::raw("CONCAT(t.nombre, ' ', t.apa) AS nombre_tutor")
            )
            ->get();
        
        // 2. Obtener los usuarios con rol 'alumno' para el dropdown
        $usuarios_candidatos = DB::table($this->userTable)
            ->where('rol', 'alumno')
            // Selecciona la columna 'id' y la renombra a 'id_usuario' para el formulario
            ->select("{$this->userIdColumn} AS id_usuario", DB::raw("CONCAT(nombre, ' ', apa) AS nombre_completo"))
            ->get();

        // 3. Obtener los usuarios con rol 'tutor' para el dropdown
        $tutores = DB::table($this->userTable)
            ->where('rol', 'tutor')
            // Selecciona la columna 'id' y la renombra a 'id_tutor' para el formulario
            ->select("{$this->userIdColumn} AS id_tutor", DB::raw("CONCAT(nombre, ' ', apa) AS nombre_completo"))
            ->get();
        return view('usuariosViews.alumno', [
            'alumnos_registrados' => $alumnos_registrados, 
            'usuarios_candidatos' => $usuarios_candidatos,
            'tutores' => $tutores
        ]);
    }

    // Procesa el formulario de registro de alumno (POST /alumnos)
    public function store(Request $request)
    {
        $validated = $request->validate([
            // La validación usa el nombre de la columna 'id'
            'id_alumno' => "required|exists:{$this->userTable},{$this->userIdColumn}", 
            'id_tutor' => "required|exists:{$this->userTable},{$this->userIdColumn}", 
            'grado_actual' => 'required|string|max:50',
            'fecha_inscrip' => 'required|date',
            'condiciones' => 'nullable|string|max:500', 
        ]);

        try {
            // Asume que la tabla de detalles de alumno se llama 'alumno'
            DB::table('alumno')->insert([
                'id_alumno' => $validated['id_alumno'],
                'id_tutor' => $validated['id_tutor'],
                'grado_actual' => $validated['grado_actual'],
                'fecha_inscrip' => $validated['fecha_inscrip'],
                'condiciones' => $validated['condiciones'],
            ]);

            return redirect()
                ->route('alumnos.index')
                ->with('sessionInsertado', 'true')
                ->with('mensaje', 'Alumno registrado con éxito.');

        } catch (\Exception $e) {
            Log::error('Error al registrar alumno: ' . $e->getMessage());
            
            return redirect()
                ->route('alumnos.index')
                ->with('sessionInsertado', 'false')
                ->with('mensaje', 'Hubo un error al intentar registrar el alumno.');
        }
    }
    
}