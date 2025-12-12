<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlumnoController extends Controller
{
    // DEFINICIONES DE TABLAS Y COLUMNAS CONSISTENTES
    private $userTable = 'usuario'; 
    private $userIdColumn = 'id';   

    public function index()
    {
        try {
            // Obtener los alumnos registrados con sus nombres y el nombre de su tutor.
            $alumnos_registrados = DB::table('alumno')
                // JOIN para el ALUMNO, usando 'usuario' y la columna 'id'
                ->join("{$this->userTable} as a", 'alumno.id_alumno', '=', "a.{$this->userIdColumn}")
                // JOIN para el TUTOR, usando 'usuario' y la columna 'id'
                ->join("{$this->userTable} as t", 'alumno.id_tutor', '=', "t.{$this->userIdColumn}")
                ->select(
                    'alumno.*',
                    // ASUMIMOS que el nombre completo está en nombre y apaterno (o 'apa')
                    DB::raw("CONCAT(a.nombre, ' ', a.apaterno) AS nombre_alumno"), 
                    DB::raw("CONCAT(t.nombre, ' ', t.apaterno) AS nombre_tutor")
                )
                ->get();
            
            // 2. Obtener los usuarios con rol 'alumno' para el dropdown
            $usuarios_candidatos = DB::table($this->userTable)
                ->where('rol', 'alumno')
                // Selecciona la columna 'id' y el nombre completo
                ->select("{$this->userIdColumn} AS id_usuario", DB::raw("CONCAT(nombre, ' ', apaterno) AS nombre_completo"))
                ->get();

            // 3. Obtener los usuarios con rol 'tutor' para el dropdown
            $tutores = DB::table($this->userTable)
                ->where('rol', 'tutor')
                // Selecciona la columna 'id' y el nombre completo
                ->select("{$this->userIdColumn} AS id_tutor", DB::raw("CONCAT(nombre, ' ', apaterno) AS nombre_completo"))
                ->get();
            
            return view('usuariosViews.alumno', compact('alumnos_registrados', 'usuarios_candidatos', 'tutores'));

        } catch (\Exception $e) {
            Log::error('Error en AlumnoController@index: ' . $e->getMessage());
            // En caso de error, retorna datos vacíos
            return view('usuariosViews.alumno', [
                'alumnos_registrados' => [], 
                'usuarios_candidatos' => [], 
                'tutores' => []
            ])->with('mensaje', 'Error al cargar datos: ' . $e->getMessage());
        }
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            // La validación usa la columna 'id' en la tabla 'usuario'
            'id_alumno' => "required|exists:{$this->userTable},{$this->userIdColumn}", 
            'id_tutor' => "required|exists:{$this->userTable},{$this->userIdColumn}", 
            'grado_actual' => 'required|string|max:50',
            'fecha_inscrip' => 'required|date',
            'condiciones' => 'nullable|string|max:500', 
        ]);
        
        try {
            DB::table('alumno')->insert([
                'id_alumno' => $validated['id_alumno'],
                'id_tutor' => $validated['id_tutor'],
                'grado_actual' => $validated['grado_actual'],
                'fecha_inscrip' => $validated['fecha_inscrip'],
                'condiciones' => $validated['condiciones'],
            ]);

            return redirect()->route('alumnos.index')->with('sessionInsertado', 'true')->with('mensaje', 'Alumno registrado con éxito.');
        } catch (\Exception $e) {
            Log::error('Error en AlumnoController@store: ' . $e->getMessage());
            return redirect()->route('alumnos.index')->with('sessionInsertado', 'false')->with('mensaje', 'Error al registrar alumno: ' . $e->getMessage());
        }
    }
    
    // ... (otros métodos) ...
}