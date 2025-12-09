<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // 🛑 Importamos la Fachada Gate

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 🛑 Aquí registramos los Gates de autorización 🛑

        // Gate para Acceso a Gestión (Roles: administrador, sensei)
        Gate::define('acceso-gestion', function ($usuario) {
            // Usamos in_array para manejar los roles en minúsculas de tu DB
            return in_array($usuario->rol, ['administrador', 'sensei']);
        });

        // Gate para Acceso Básico (Cualquier usuario autenticado)
        Gate::define('acceso-basico', function ($usuario) {
            // Ya que el usuario está autenticado al llegar aquí, esto siempre será true
            // para Principal y Pagos.
            return in_array($usuario->rol, ['administrador', 'sensei', 'tutor', 'alumno']);
        });
    }
}