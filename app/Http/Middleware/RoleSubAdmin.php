<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleSubAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::guest()) {
            if (Auth::user()->role != "subadmin" &&  Auth::user()->role != "admin" ) {
                return redirect()->guest('/admin/reservas');
            }
        }else{
            return redirect()->guest('login');
        }
        return $next($request);
    }
}
