<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PersonaController extends Controller
{
    public function index()
    {
        $personas = DB::select('CALL sp_listado_usuarios()');

        return view('personas.index', compact('personas'));
    }
}



