<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlumnoController extends Controller
{
    // DEFINICIONES DE TABLAS Y COLUMNAS CONSISTENTES
    private $userTable = 'usuario'; 
    private $userIdColumn = 'id_usuario';   

    public function index()
    {
        try {
            // Obtener los alumnos registrados con sus nombres y el nombre de su tutor.
            $alumnos_registrados = DB::table('alumno')
                // JOIN para el ALUMNO, usando 'usuario' y la columna 'id'
                ->join("{$this->userTable} as a", 'alumno.id_alumno', '=', "a.{$this->userIdColumn}")
                // JOIN para el TUTOR, usando 'usuario' y la columna 'id'
                ->join("{$this->userTable} as t", 'alumno.id_Tutor', '=', "t.{$this->userIdColumn}")
                // JOIN para el GRADO
                ->join('grado as g', 'alumno.id_Grado', '=', 'g.id_grado')
                ->select(
                    'alumno.*',
                    'alumno.condiciones_medicas',
                    'g.nombreGrado',
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

                $grados = DB::table('grado')->orderBy('orden', 'asc')->get();

            // 3. Obtener los usuarios con rol 'tutor' para el dropdown
            $tutores = DB::table($this->userTable)
                ->where('rol', 'tutor')
                // Selecciona la columna 'id' y el nombre completo
                ->select("{$this->userIdColumn} AS id_Tutor", DB::raw("CONCAT(nombre, ' ', apaterno) AS nombre_completo"))
                ->get();

            return view('usuariosViews.alumno', compact('alumnos_registrados', 'usuarios_candidatos', 'tutores', 'grados'));

        } catch (\Exception $e) {
            Log::error('Error en AlumnoController@index: ' . $e->getMessage());
            // En caso de error, retorna datos vacíos
            return view('usuariosViews.alumno', [
                'alumnos_registrados' => [], 
                'usuarios_candidatos' => [], 
                'tutores' => [],
                'grados' => []
            ])->with('mensaje', 'Error al cargar datos: ' . $e->getMessage());
        }
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            // La validación usa la columna 'id' en la tabla 'usuario'
            'id_alumno' => "required|exists:{$this->userTable},{$this->userIdColumn}", 
            'id_Tutor' => "required|exists:{$this->userTable},{$this->userIdColumn}", 
            'grado' => 'required|integer|exists:grado,id_grado',
            'Fecha_inscrip' => 'required|date',
            'documento_medico' => 'required|file|mimes:pdf|max:5120', // Máximo 5MB
        ]);
        
        try {

         $rutaDocumento = null;
        
        if ($request->hasFile('documento_medico')) {
            $archivo = $request->file('documento_medico');
            
            // Generar nombre único para el archivo
            $nombreArchivo = 'medico_' . $validated['id_alumno'] . '_' . time() . '.pdf';
            
            // Guardar en storage/app/public/documentos_medicos
            $rutaDocumento = $archivo->storeAs('documentos_medicos', $nombreArchivo, 'public');
            
            // La URL que se guardará en la BD
            // storage/documentos_medicos/medico_123_1234567890.pdf
        }
            DB::table('alumno')->insert([
                'id_alumno' => $validated['id_alumno'],
                'id_Tutor' => $validated['id_Tutor'],
                'id_Grado' => $validated['grado'],
                'Fecha_inscrip' => $validated['Fecha_inscrip'],
                'condiciones_medicas' => $rutaDocumento,
            ]);

            return redirect()->route('alumnos.index')->with('success', 'Alumno registrado con éxito.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al registrar: ' . $e->getMessage());
        }
    }
}