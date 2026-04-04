<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    // ── Tabla y PK reales en BD ───────────────────────────────────────
    protected $table      = 'usuario';
    protected $primaryKey = 'id_usuario';
    public    $timestamps = false; // La tabla no tiene created_at/updated_at estándar

    // ── Columnas asignables ───────────────────────────────────────────
    protected $fillable = [
        'nombre',
        'apaterno',
        'amaterno',
        'fecha_naci',
        'telefono',
        'correo',
        'pass',
        'rol',
        'estado',
        'fecha_registro',
    ];

    // ── Castear tipos para evitar comparaciones string vs integer ────
    protected $casts = [
        'estado'         => 'integer',
        'id_usuario'     => 'integer',
        'fecha_naci'     => 'date',
        'fecha_registro' => 'datetime',
    ];

    // ── Ocultar pass en serialización JSON ────────────────────────────
    protected $hidden = ['pass'];

    // ── Auth: Laravel busca 'password' por defecto, la columna real es 'pass' ──
    public function getAuthPassword(): string
    {
        return $this->pass;
    }

    // ── Auth: identificador único para login (columna 'correo', no 'email') ──
    public function getAuthIdentifierName(): string
    {
        return 'id_usuario';
    }

    // ── Scopes de utilidad ────────────────────────────────────────────
    public function scopeActivos($query)
    {
        return $query->where('estado', 1);
    }

    public function scopePorRol($query, string $rol)
    {
        return $query->where('rol', $rol);
    }

    // ── Relaciones ────────────────────────────────────────────────────
    public function registroFisico()
    {
        return $this->hasOne(\App\Models\RegistroFisico::class, 'id_usuario', 'id_usuario');
    }

    public function historialGrados()
    {
        return $this->hasMany(\App\Models\HistorialGrado::class, 'id_usuario', 'id_usuario')
                    ->orderBy('fecha_obtencion', 'desc');
    }

    public function pagos()
    {
        return $this->hasMany(\App\Models\Pago::class, 'id_usuario', 'id_usuario')
                    ->orderBy('fecha_pago', 'desc');
    }

    public function asistencias()
    {
        return $this->hasMany(\App\Models\Asistencia::class, 'id_usuario', 'id_usuario');
    }

    // ── Helpers ───────────────────────────────────────────────────────
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apaterno} {$this->amaterno}");
    }

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function esSensei(): bool
    {
        return $this->rol === 'sensei';
    }

    public function esTutor(): bool
    {
        return $this->rol === 'tutor';
    }

    public function esAlumno(): bool
    {
        return $this->rol === 'alumno';
    }
}