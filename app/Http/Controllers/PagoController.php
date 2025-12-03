<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PagoController extends Controller
{
    // Método para LISTAR pagos y mostrar el formulario (GET /pagos)
   // app/Http/Controllers/PagoController.php

public function index()
{
    // 1. Obtener pagos, haciendo JOIN
    $pagos = DB::connection('mysql')
        ->table('pago')
        // CORREGIDO: Usar 'usuarios'
        ->join('usuarios', 'pago.id_alumno', '=', 'usuarios.id_usuario') // Asume 'id_usuario' como PK
        ->select(
            'pago.*',
            DB::raw("CONCAT(usuarios.nombre, ' ', usuarios.apa) AS nombre_alumno")
        )
        ->get();

    // 2. Obtener lista de ALUMNOS para el dropdown
    $alumnos = DB::connection('mysql')
        ->table('usuarios') // <-- CORREGIDO: Usar 'usuarios'
        ->where('rol', 'alumno')
        ->select('id_usuario AS id_alumno', DB::raw("CONCAT(nombre, ' ', apa) AS nombre_completo"))
        ->get();
    
    // ...
}

        // 3. Mostrar la vista, pasándole los datos
        return view('pagosViews.pagos', [
            'pagos' => $pagos, 
            'alumnos' => $alumnos // Lista de alumnos para el select
        ]);
    }
    // Método para INSERTAR un nuevo pago (POST /pagos)
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Valida que el alumno exista en la tabla 'alumno'
            'id_alumno' => 'required|exists:alumno,id_alumno', 
            'tipo' => 'required|string|max:50',
            'monto' => 'required|numeric|min:0',
            'fechaPago' => 'required|date',
            'motivoPago' => 'required|string|max:255',
            'referenciaPago' => 'nullable|string|max:255',
            'estadoPago' => 'required|in:Pendiente,Completado,Fallido', 
        ]);

        try {
            DB::connection('mysql')
                ->table('pago')
                ->insert([
                    'id_alumno' => $validated['id_alumno'],
                    'tipo' => $validated['tipo'],
                    'monto' => $validated['monto'],
                    'fechaPago' => $validated['fechaPago'],
                    'motivoPago' => $validated['motivoPago'],
                    'referenciaPago' => $validated['referenciaPago'],
                    'estadoPago' => $validated['estadoPago'],
                ]);

            return redirect()
                ->route('pagos.index')
                ->with('sessionInsertado', 'true')
                ->with('mensaje', 'Pago registrado con éxito.');

        } catch (\Exception $e) {
            Log::error('Error al registrar pago: ' . $e->getMessage());
            
            return redirect()
                ->route('pagos.index')
                ->with('sessionInsertado', 'false')
                ->with('mensaje', 'Hubo un error al intentar registrar el pago.');
        }
    }
    
    // Dejamos los otros métodos (show, update, destroy) para una implementación posterior
    public function show($id){}
    public function edit($id){}
    public function update(Request $request, $id){}
    public function destroy($id){}
}