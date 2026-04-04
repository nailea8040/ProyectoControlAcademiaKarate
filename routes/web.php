<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\PerfilController;

// ── LANDING ──────────────────────────────────────────────────────────────────
// Ruta principal y alias /landing — ambas sirven la misma vista
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Alias para compatibilidad — algunas vistas y redirects usan /landing
Route::get('/landing', function () {
    return view('landing');
});

Route::post('/contacto/enviar', [ContactoController::class, 'enviar'])->name('contacto.enviar');

// ── AUTENTICACIÓN (públicas) ──────────────────────────────────────────────────
Route::get('/login',    [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login',   [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
// Si alguien accede a /logout por GET (ej: escribiendo la URL), redirigir al login
Route::get('/logout', function () {
    return redirect()->route('login');
});
Route::get('/ver-login', [UsuarioController::class, 'VerLogin'])->name('verLogin');

// ── REGISTRO ──────────────────────────────────────────────────────────────────
Route::get('/registro',  [RegistroController::class, 'create'])->name('registro.create');
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');

// ── RECUPERACIÓN DE CONTRASEÑA (públicas) ─────────────────────────────────────
Route::get('/olvido-contrasennia',       [ResetPasswordController::class, 'showResetForm'])->name('password.request');
Route::post('/olvido-contrasennia',      [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}',   [ResetPasswordController::class, 'showResetFormWithToken'])->name('password.reset');
Route::put('/password/update',          [ResetPasswordController::class, 'resetPassword'])->name('password.update');

// ── GALERÍA (index público, store/destroy protegidos) ─────────────────────────
Route::get('/galeria', [GaleriaController::class, 'index'])->name('galeria.index');

Route::middleware('auth')->group(function () {
    Route::post('/galeria',                  [GaleriaController::class, 'store'])->name('galeria.store');
    Route::delete('/galeria/{id}',           [GaleriaController::class, 'destroy'])->name('galeria.destroy');
    Route::delete('/galeria-evento',         [GaleriaController::class, 'destroyEvento'])->name('galeria.destroyEvento');
});

// ── CALENDARIO (index público, store/update/destroy protegidos) ───────────────
Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index');

Route::middleware('auth')->group(function () {
    Route::post('/calendario',          [CalendarioController::class, 'store'])->name('calendario.store');
    // PK real de la tabla calendario es id_cal → rutas usan /calendario/{id}
    Route::put('/calendario/{id}',      [CalendarioController::class, 'update'])->name('calendario.update');
    Route::delete('/calendario/{id}',   [CalendarioController::class, 'destroy'])->name('calendario.destroy');
});

// ── RUTAS PROTEGIDAS (auth) ───────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/principal', function () {
        return view('usuariosViews.principal');
    })->name('principal');

    // Perfil
    Route::get('/perfil',    [PerfilController::class, 'index'])->name('perfil');
    Route::put('/perfil',    [PerfilController::class, 'update'])->name('perfil.update');

    // Usuarios
    Route::get('/usuarios',              [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::post('/usuarios',             [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{id}/edit',    [UsuarioController::class, 'edit'])->name('editarUsu');
    Route::put('/usuarios/{id}',         [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}',      [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    Route::post('/usuarios/{id}/toggle-active', [UsuarioController::class, 'toggleActive'])->name('usuarios.toggleActive');

    // Alumnos
    Route::get('/alumnos',              [AlumnoController::class, 'index'])->name('alumnos.index');
    Route::post('/alumnos',             [AlumnoController::class, 'store'])->name('alumnos.store');
    Route::put('/alumnos/{id}',         [AlumnoController::class, 'update'])->name('alumnos.update');
    Route::get('/alumnos/{id}/historial', [AlumnoController::class, 'historialGrados'])->name('alumnos.historial');

    // Pagos
    Route::get('/pagos',    [PagoController::class, 'index'])->name('pagos.index');
    Route::post('/pagos',   [PagoController::class, 'store'])->name('pagos.store');
    Route::get('/pagos/{id}/historial', [PagoController::class, 'historialAlumno'])->name('pagos.historial');

    // Tutores
    Route::get('/tutor',          [TutorController::class, 'index'])->name('tutor.index');
    Route::post('/tutor',         [TutorController::class, 'store'])->name('tutor.store');
    Route::put('/tutor/{id}',     [TutorController::class, 'update'])->name('tutor.update');

    // Eventos multimedia (tabla evento: imagen/video)
    Route::get('/eventos',          [EventoController::class, 'index'])->name('eventos.index');
    Route::post('/eventos',         [EventoController::class, 'store'])->name('eventos.store');
    Route::put('/eventos/{id}',     [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{id}',  [EventoController::class, 'destroy'])->name('eventos.destroy');
});