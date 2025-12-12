<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\TutorController; 
use App\Http\Controllers\UsuarioController; 


use App\Http\Controllers\ResetPasswordController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('principal', function () {
    return view('usuariosViews.principal'); 

})->name('principal');

Route::resource('usuarios', UsuarioController::class)->except(['create', 'show'])
    ->names([
        'edit' => 'editarUsu',
    ]);

// --- RUTA PARA USUARIOS (Base) ---
Route::resource('usuarios', UsuarioController::class)->only([
    'index', 
    'store' 
]);

Route::resource('usuarios', UsuarioController::class)->except(['create', 'show'])
->names([
        'edit' => 'editarUsu',
    ]);

// --- RUTAS PARA ALUMNOS ---
// Define GET /alumnos (listado) y POST /alumnos (inserción)
Route::resource('alumnos', AlumnoController::class)->only([
    'index', 
    'store'
]);

// --- RUTAS PARA PAGOS ---
// Define GET /pagos (listado) y POST /pagos (inserción)
Route::resource('pagos', PagoController::class)->only([
    'index', 
    'store'
]);

// --- RUTA PARA TUTORES (opcional, si ya lo estás implementando) ---
// Define GET /tutor (listado) y POST /tutor (inserción)
Route::resource('tutor', TutorController::class)->only([
    'index', 
    'store'
]);

// Mantenemos la ruta de confirmación si es necesaria
Route::get('/confirmar-correo/{correo}', [UsuarioController::class, 'confirmMail'])->name('usuarios.confirm');
// routes/web.php

// 1. RUTA GET: Muestra el formulario de inicio de sesión (La que ya tienes)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// 2. RUTA POST: Maneja la validación de las credenciales (¡Esta es la que falta!)
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate'); 

// Procesa el formulario de login (donde se enviará la solicitud POST)
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

// Rutas de autenticación (Generalmente van fuera del middleware 'auth' ya que es la salida)

// 1. Ruta POST para el cierre de sesión (segura)
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('logout', function () {
    return view('auth.logout_confirmation'); // Puedes crear una vista de confirmación o simplemente redirigir
})->name('logout.show');

Route::get('/resetpass', function () {
    return view('ResetPasswordViews/olvidosucontrasennia');
})->name('password.request'); 

Route::put('/resetpass', [ResetPasswordController::class,'sendResetLinkEmail'])->name ('pass'); 

/*
// Muestra el formulario de "Olvidó su contraseña"
Route::get('forgot-password', [ResetPasswordController::class, 'showResetForm'])->name('password.request');

// Procesa el formulario y envía el enlace al correo
Route::put('forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Muestra el formulario para cambiar la contraseña usando el token
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetFormWithToken'])->name('password.reset');

// Procesa el formulario para guardar la nueva contraseña
Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');

// routes/web.php*/


Route::middleware('auth')->group(function () {
    
    // 1. Ruta Principal
    Route::get('principal', function () {
        return view('usuariosViews.principal'); 
    })->name('principal');
    
    // 2. Rutas de Gestión (Pagos, Usuarios, etc.)
    Route::resource('pagos', PagoController::class)->only(['index', 'store']);

    // ... y el resto de tus Route::resource (usuarios, alumnos, tutor) aquí
    
});

