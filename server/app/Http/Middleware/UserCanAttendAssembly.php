<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserCanAttendAssembly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$request->user()->canAttend($request->assembly)) {
            abort(401, 'No tens accés a aquest document');
        }

        return $next($request);
    }
}
