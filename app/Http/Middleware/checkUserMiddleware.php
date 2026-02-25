<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

use function Laravel\Prompts\alert;

class checkUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,...$role): Response
    {
        //Check the user do not login will redirect to login page
        if(!auth()->check()){
            return redirect()->route('login');
        }

        if(!in_array(auth()->user()->role, $role)){
            abort(403,"Unauthorized action");
        }
        // if (!Auth::check()) {
        //     return redirect()->route('login');
        // }
        // $user = Auth::user();
        // if (!in_array($user->role->value, $roles)) {
        //     abort(403, 'Forbidden');
        // }
        return $next($request);
    }
}
