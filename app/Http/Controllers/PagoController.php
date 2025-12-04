<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PagoController extends Controller
{
    private $userTable = 'usuario'; // <-- ¡CORREGIDO!
    private $userIdColumn = 'id';    // <-- Asumimos 'id'

    public function index()
    {
        try {
            // 1. Obtener pagos, haciendo JOIN
            $pagos = DB::table('pago')
                // JOIN a la tabla 'usuario' (singular)
                ->join($this->userTable, 'pago.id_alumno', '=', "{$this->userTable}.{$this->userIdColumn}") 
                ->select(
                    'pago.*',
                    // Obtiene el nombre completo del alumno para la tabla
                    DB::raw("CONCAT({$this->userTable}.nombre, ' ', {$this->userTable}.apaterno) AS nombre_alumno")
                )
                ->get();

            // 2. Obtener lista de ALUMNOS para el dropdown
            $alumnos = DB::table($this->userTable)
                ->where('rol', 'alumno')
                // Selecciona 'id' y lo renombra y obtiene el nombre completo
                ->select("{$this->userIdColumn} AS id_alumno", DB::raw("CONCAT(nombre, ' ', apaterno) AS nombre_completo"))
                ->get();
            
            return view('pagosViews.pagos', compact('pagos', 'alumnos'));

        } catch (\Exception $e) {
            Log::error('Error en PagoController@index: ' . $e->getMessage());
            return view('pagosViews.pagos', ['pagos' => [], 'alumnos' => []])
                   ->with('mensaje', 'Error al cargar datos: ' . $e->getMessage());
        }
    }
    
    // ... (otros métodos) ...
}