<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\TutorController; // Si lo necesitas para el controlador de tutores
use App\Http\Controllers\UsuarioController; // Si no estaba

// Ruta principal (sin cambios)
Route::get('/', function () {
    return view('welcome');
});

// --- RUTA PARA USUARIOS (Base) ---
Route::resource('usuarios', UsuarioController::class)->only([
    'index', 
    'store' 
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

// NOTA: Asegúrate de reemplazar LoginController y los nombres de los métodos
// por los que realmente usas en tu proyecto.
