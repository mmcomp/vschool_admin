<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class LogoutUsers
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
        $user = Auth::user();
        if($user && $user->logout) {
            $user->logout = false;
            $user->save();
            Auth::logout();
            return redirect('/');
        }
        return $next($request);
    }
}
