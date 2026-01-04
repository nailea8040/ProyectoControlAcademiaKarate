<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Simulando los datos que antes manejaba el useEffect en React
        $totalAlumnos = Alumno::where('status', 'activo')->count();
        $totalMaestros = 3; // O Instructor::count();
        $mesesTrayectoria = 15;

        return view('dashboard.index', compact('totalAlumnos', 'totalMaestros', 'mesesTrayectoria'));
    }
}
