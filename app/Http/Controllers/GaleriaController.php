<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GaleriaController extends Controller
{
    /**
     * Mostrar la galería
     */
    public function index()
    {
        // Obtener todos los archivos multimedia ordenados por fecha
        $archivos = DB::table('galeria')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('galeria', compact('archivos'));
    }

    /**
     * Almacenar un nuevo archivo multimedia
     */
    public function store(Request $request)
    {
        // Validar que solo el administrador pueda subir archivos
        if (Auth::user()->rol !== 'administrador') {
            return back()->with('error', 'No tienes permisos para subir archivos.');
        }

        // Validar los datos
        $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|in:image,video',
            'archivo' => 'required|file|max:51200', // 50MB máximo
            'descripcion' => 'nullable|string'
        ]);

        // Validar el tipo de archivo según la categoría
        if ($request->tipo === 'image') {
            $request->validate([
                'archivo' => 'mimes:jpeg,jpg,png|max:10240' // 10MB para imágenes
            ]);
        } else {
            $request->validate([
                'archivo' => 'mimes:mp4|max:51200' // 50MB para videos
            ]);
        }

        // Subir el archivo
        $archivo = $request->file('archivo');
        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
        
        // Guardar en storage/app/public/galeria
        $ruta = $archivo->storeAs('galeria', $nombreArchivo, 'public');

        // Guardar en la base de datos
        DB::table('galeria')->insert([
            'titulo' => $request->titulo,
            'tipo' => $request->tipo,
            'ruta' => $ruta,
            'descripcion' => $request->descripcion,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('mensaje', 'Archivo subido exitosamente.');
    }

    /**
     * Eliminar un archivo multimedia
     */
    public function destroy($id)
    {
        // Validar que solo el administrador pueda eliminar
        if (Auth::user()->rol !== 'administrador') {
            return back()->with('error', 'No tienes permisos para eliminar archivos.');
        }

        // Obtener el archivo de la base de datos
        $archivo = DB::table('galeria')->where('id_gal', $id)->first();

        if (!$archivo) {
            return back()->with('error', 'Archivo no encontrado.');
        }

        // Eliminar el archivo físico del storage
        if (Storage::disk('public')->exists($archivo->ruta)) {
            Storage::disk('public')->delete($archivo->ruta);
        }

        // Eliminar el registro de la base de datos
        DB::table('galeria')->where('id_gal', $id)->delete();

        return back()->with('mensaje', 'Archivo eliminado exitosamente.');
    }
}