<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

// Ruta principal (sin cambios)
Route::get('/', function () {
    return view('welcome');
});

// Simplificamos las rutas de registro/listado y quitamos el prefijo /login
// Usamos el recurso 'usuarios'
Route::resource('usuarios', UsuarioController::class)->only([
    'index', // GET /usuarios (Muestra el formulario y la tabla de listado)
    'store'  // POST /usuarios (Guarda el nuevo usuario)
]);

// Mantenemos la ruta de confirmación si es necesaria
Route::get('/confirmar-correo/{correo}', [UsuarioController::class, 'confirmMail'])->name('usuarios.confirm');

// Mantenemos la ruta de login para el formulario de login (si es distinto al de registro)
Route::prefix("/login")->group(function(){ 
    Route::get('/', [UsuarioController::class,'VerLogin'])->name('login.view');
});

