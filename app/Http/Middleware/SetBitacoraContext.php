<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class SetBitacoraContext
{
    public function handle($request, Closure $next)
    {
        if (session()->has('id_persona')) {
            DB::statement('SET @usuario = ?', [session('id_persona')]);
            DB::statement('SET @ip = ?', [$request->ip()]);
        }

        return $next($request);
    }
}
