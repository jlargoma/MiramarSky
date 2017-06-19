<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
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
        $arraySubadminUrl = [
                                'admin/precios',
                            ];
        if (!Auth::guest()) {
            if (Auth::user()->role == "propietario") {

                return redirect()->guest('/admin/propietario');

            }elseif(Auth::user()->role == "subAdmin" && !array_search( $request->path(), $arraySubadminUrl )){
                return redirect()->guest('/admin/jaime');
            }
        }else{
            return redirect()->guest('login');
        }
        return $next($request);
    }
}
