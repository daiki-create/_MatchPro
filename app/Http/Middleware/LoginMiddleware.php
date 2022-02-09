<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 未ログイン
        if(!session()->has('login')){
            return redirect(url('/'));
        }

        $login = session()->get('login');
        $coach_flag = session()->get('coach_flag');

        $request->merge([
            'login' => $login,
            'coach_flag' => $coach_flag
        ]);

        return $next($request);
    }
}
