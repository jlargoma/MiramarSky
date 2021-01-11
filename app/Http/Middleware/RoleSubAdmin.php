<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleSubAdmin {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next) {
    if (!Auth::guest()) {
      $role = Auth::user()->role;

      $roles1 = [
          'subadmin',
          'admin',
          'agente',
          'recepcionista',
          'conserje',
          'limpieza'
      ];

      if (in_array($role, $roles1)) {
        if ($role == "limpieza") {
          return redirect()->guest('/admin/limpieza');
        }
        return $next($request);
      }

      $room = \App\Rooms::where('owned', Auth::user()->id)->first();
      if ($room) {
        return redirect()->guest('/admin/propietario/' . $room->nameRoom);
      }
      return redirect()->guest('/admin/propietario/');
    } else {
      return redirect()->guest('login');
    }
    return $next($request);
  }

}
