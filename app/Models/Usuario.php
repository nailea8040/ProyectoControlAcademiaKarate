<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable // Cambiado de 'User' a 'Usuario'
{
    use HasFactory, Notifiable;

    // 🛑 1. Nombre de la tabla
    protected $table = 'usuario'; 

    // 🛑 2. Llave primaria
    protected $primaryKey = 'id_usuario';

    // 🛑 3. Atributos que pueden ser asignados masivamente (Asegúrate de incluir 'rol')
    protected $fillable = [
        'nombre', // Coincide con tu columna 'nombre'
        'apaterno',
        'amaterno',
        'fecha_naci',
        'tel',
        'correo', // Coincide con tu columna 'correo'
        'pass',
        'rol',    // **CRÍTICO: Incluir 'rol' para que se guarde**
        'fecha_registro',
        'estado', // Asegúrate de incluir el campo 'activo' si lo usas
        
    ];

    // 🛑 4. Atributos ocultos al serializar (usando 'pass' en lugar de 'password')
    protected $hidden = [
        'pass',
        'remember_token',
    ];

    // 🛑 5. Define la columna de contraseña para que use 'pass' (Sobrescribe el valor por defecto 'password')
    public function getAuthPasswordName()
    {
        return 'pass';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // No tienes 'email_verified_at', pero sí debes hashear la contraseña
            'pass' => 'hashed', 
            'estado' => 'boolean',
        ];
    }
}