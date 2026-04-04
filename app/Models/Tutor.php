<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    // La tabla tutor usa id_tutor como PK que es FK → usuario.id_usuario
    // Es una relación 1:1 extendida: un usuario con rol='tutor' tiene un registro en tutor
    protected $table      = 'tutor';
    protected $primaryKey = 'id_tutor';
    public    $incrementing = false; // no es auto-increment, es FK
    public    $timestamps   = false;

    protected $fillable = [
        'id_tutor',       // FK → usuario.id_usuario
        'id_ocupacion',
        'relacion_estudiante',
    ];

    protected $casts = [
        'id_tutor'     => 'integer',
        'id_ocupacion' => 'integer',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    /** Usuario base del tutor */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_tutor', 'id_usuario');
    }

    /** Ocupación del tutor */
    public function ocupacion()
    {
        return $this->belongsTo(Ocupacion::class, 'id_ocupacion', 'id_ocupacion');
    }
}