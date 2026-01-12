<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PerfilController extends Controller
{
    /**
     * Mostrar el perfil del usuario autenticado
     */
    public function index()
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();
        
        // Retornar la vista con los datos del usuario
        return view('perfil', compact('usuario'));
    }

    /**
     * Actualizar el perfil del usuario
     */
    public function update(Request $request)
    {
        $usuario = Auth::user();

        // Validar los datos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apaterno' => 'required|string|max:255',
            'amaterno' => 'required|string|max:255',
            'fecha_naci' => 'required|date',
            'correo' => 'required|email|unique:usuarios,correo,' . $usuario->id_usuario . ',id_usuario',
            'tel' => 'required|string|min:10|max:10',
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'apaterno.required' => 'El apellido paterno es obligatorio',
            'amaterno.required' => 'El apellido materno es obligatorio',
            'fecha_naci.required' => 'La fecha de nacimiento es obligatoria',
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'Debe ser un correo válido',
            'correo.unique' => 'Este correo ya está registrado',
            'tel.required' => 'El teléfono es obligatorio',
            'tel.min' => 'El teléfono debe tener 10 dígitos',
            'tel.max' => 'El teléfono debe tener 10 dígitos',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        ]);

        // Actualizar datos del usuario
        $usuario->nombre = $validated['nombre'];
        $usuario->apaterno = $validated['apaterno'];
        $usuario->amaterno = $validated['amaterno'];
        $usuario->fecha_naci = $validated['fecha_naci'];
        $usuario->correo = $validated['correo'];
        $usuario->tel = $validated['tel'];

        // Si se proporcionó una nueva contraseña, actualizarla
        if ($request->filled('password')) {
            $usuario->pass = Hash::make($validated['password']);
        }

        // Guardar cambios
        $usuario->save();

        // Redireccionar con mensaje de éxito
        return redirect()->route('perfil')
            ->with('success', 'Perfil actualizado correctamente');
    }

    /**
     * Cambiar foto de perfil (opcional - para implementación futura)
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $usuario = Auth::user();

        // Procesar la imagen
        if ($request->hasFile('avatar')) {
            $imagen = $request->file('avatar');
            $nombreImagen = 'avatar_' . $usuario->id_usuario . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('avatars'), $nombreImagen);
            
            // Guardar la ruta en la base de datos
            $usuario->avatar = 'avatars/' . $nombreImagen;
            $usuario->save();
        }

        return redirect()->route('perfil')
            ->with('success', 'Foto de perfil actualizada correctamente');
    }
}