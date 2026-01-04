<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // IMPORTANTE: Agrega esta línea

class CalendarioController extends Controller
{
    public function index()
    {
        // 1. Consultar todos los eventos de la base de datos
        $eventos = DB::table('eventos')->get();

        // 2. Pasar la variable $eventos a la vista 'calendario'
        return view('calendario', compact('eventos'));
    }
}