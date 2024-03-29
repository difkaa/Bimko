<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle($request, Closure $next, $permission = null, ... $roles)
    // {
    //     foreach ($roles as $role) {
    //         if (!$request->user()->hasRole($role)) {
    //             abort(404);
    //         }
    //     }


    //     if($permission != null && !$request->user()->can($permission)) {
    //         abort(404);
    //     }

    //     return $next($request);
    // }

    public function handle($request, Closure $next, ... $roles)
{
    if (!Auth::check()) // I included this check because you have it, but it really should be part of your 'auth' middleware, most likely added as part of a route group.
        return redirect('login');

    $user = Auth::user();

    foreach($roles as $role) {
        if($user->hasRole($role))
            return $next($request);
    }

    return redirect('login');
}
}
