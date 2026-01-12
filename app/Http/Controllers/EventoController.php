<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    /**
     * Almacenar un nuevo evento
     */
    public function store(Request $request)
    {
        // 1. Validar que solo el admin pueda hacerlo
        if (Auth::user()->rol !== 'administrador') {
            return back()->with('error', 'No tienes permisos.');
        }

        // 2. Validar los datos
        $request->validate([
            'titulo'      => 'required|string|max:100',
            'fecha'       => 'required|date',
            'hora'        => 'required',
            'ubicacion'   => 'required|string',
            'tipo'        => 'required|in:tournament,exam,class,seminar',
            'descripcion' => 'nullable|string'
        ]);

        // 3. Insertar en la base de datos
        DB::table('eventos')->insert([
            'titulo'      => $request->titulo,
            'fecha'       => $request->fecha,
            'hora'        => $request->hora,
            'ubicacion'   => $request->ubicacion,
            'tipo'        => $request->tipo,
            'descripcion' => $request->descripcion,
            'created_at'  => now(),
            'updated_at'  => now()
        ]);

        return back()->with('mensaje', 'Evento creado con éxito');
    }

    /**
     * Actualizar un evento existente
     */
    public function update(Request $request, $id)
    {
        // 1. Validar que solo el admin pueda hacerlo
        if (Auth::user()->rol !== 'administrador') {
            return back()->with('error', 'No tienes permisos para editar eventos.');
        }

        // 2. Validar los datos
        $request->validate([
            'titulo'      => 'required|string|max:100',
            'fecha'       => 'required|date',
            'hora'        => 'required',
            'ubicacion'   => 'required|string',
            'tipo'        => 'required|in:tournament,exam,class,seminar',
            'descripcion' => 'nullable|string'
        ]);

        // 3. Actualizar el evento en la base de datos
        $updated = DB::table('eventos')
            ->where('id', $id)
            ->update([
                'titulo'      => $request->titulo,
                'fecha'       => $request->fecha,
                'hora'        => $request->hora,
                'ubicacion'   => $request->ubicacion,
                'tipo'        => $request->tipo,
                'descripcion' => $request->descripcion,
                'updated_at'  => now()
            ]);

        if ($updated) {
            return back()->with('mensaje', 'Evento actualizado con éxito');
        } else {
            return back()->with('error', 'No se pudo actualizar el evento');
        }
    }

    /**
     * Eliminar un evento
     */
    public function destroy($id)
    {
        // 1. Validar que solo el admin pueda hacerlo
        if (Auth::user()->rol !== 'administrador') {
            return back()->with('error', 'No tienes permisos para eliminar eventos.');
        }

        // 2. Eliminar el evento de la base de datos
        $deleted = DB::table('eventos')->where('id', $id)->delete();

        if ($deleted) {
            return back()->with('mensaje', 'Evento eliminado con éxito');
        } else {
            return back()->with('error', 'No se pudo eliminar el evento');
        }
    }
}