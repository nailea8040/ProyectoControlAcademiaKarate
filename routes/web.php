<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\TutorController; 
use App\Http\Controllers\UsuarioController; 
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\GaleriaController;

Route::get('/', function () {
    return view('welcome');
});

// Ruta pública para ver la galería
Route::get('/galeria', [GaleriaController::class, 'index'])->name('galeria.index');

// Rutas protegidas solo para administradores
Route::middleware(['auth'])->group(function () {
    
    // Subir archivo a la galería
    Route::post('/galeria', [GaleriaController::class, 'store'])->name('galeria.store');
    
    // Eliminar archivo de la galería
    Route::delete('/galeria/{id}', [GaleriaController::class, 'destroy'])->name('galeria.destroy');
    
});

Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index');

Route::middleware(['auth'])->group(function () {
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    Route::put('/eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{id}', [EventoController::class, 'destroy'])->name('eventos.destroy');
});

Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::get('/login', function() {
    return view('auth.login'); // Asegúrate de que este archivo exista
})->name('login');


Route::get('landing', function () {
    return view('landing');
})->name('landing');

// --- RUTAS DE AUTENTICACIÓN (SIN MIDDLEWARE) ---

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

// Logout (requiere autenticación)
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// --- RUTAS DE RECUPERACIÓN DE CONTRASEÑA ---

// 1. Muestra el formulario para solicitar recuperación
Route::get('/olvido-contrasennia', [ResetPasswordController::class, 'showResetForm'])->name('password.request');

// 2. Procesa la solicitud y envía el correo
Route::post('/olvido-contrasennia', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// 3. Muestra el formulario para cambiar la contraseña (con token)
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetFormWithToken'])->name('password.reset');

// 4. Procesa el cambio de contraseña
Route::put('/password/update', [ResetPasswordController::class, 'resetPassword'])->name('password.update');

// Confirmación de correo
Route::get('/confirmar-correo/{correo}', [UsuarioController::class, 'confirmMail'])->name('usuarios.confirm');

// --- RUTAS PROTEGIDAS (CON MIDDLEWARE AUTH) ---
Route::middleware('auth')->group(function () {
    
    // Página principal
    Route::get('principal', function () {
        return view('usuariosViews.principal'); 
    })->name('principal');
    
    // Usuarios
    Route::resource('usuarios', UsuarioController::class)->only(['index', 'store']);
    Route::resource('usuarios', UsuarioController::class)->except(['create', 'show'])->names([
        'edit' => 'editarUsu',
    ]);
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    // Ruta para cambiar el estado de activo/inactivo (Método POST o PUT/PATCH)
Route::post('/usuarios/{id}/toggle-active', [UsuarioController::class, 'toggleActive'])->name('usuarios.toggleActive');    
    
    // Alumnos
    Route::resource('alumnos', AlumnoController::class)->only(['index', 'store']);
    
    // Pagos
    Route::resource('pagos', PagoController::class)->only(['index', 'store']);
    
    // Tutores
    Route::resource('tutor', TutorController::class)->only(['index', 'store']);

    // Ruta de Perfil - NUEVA
    Route::get('/perfil', [App\Http\Controllers\PerfilController::class, 'index'])
        ->name('perfil');
});