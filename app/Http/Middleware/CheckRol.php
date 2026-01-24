<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRol
{
public function handle($request, Closure $next, ...$roles)
{
    if (!session()->has('rol')) {
        return redirect('/login');
    }

    if (!in_array(session('rol'), $roles)) {
        abort(403);
    }

    return $next($request);
}
}


