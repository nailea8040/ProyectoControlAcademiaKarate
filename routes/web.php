<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\TutorController; 
use App\Http\Controllers\UsuarioController; 


Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
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

// 2. Ruta GET (opcional) para direccionar al formulario de cierre de sesión
// Esta ruta es la que usaremos en el enlace <a href="..."> en el Blade.
// Luego, usaremos un formulario oculto en el Blade para disparar la solicitud POST.
Route::get('logout', function () {
    return view('auth.logout_confirmation'); // Puedes crear una vista de confirmación o simplemente redirigir
})->name('logout.show');

