<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditorRoutes
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
        if (Auth::check()) {
            if (Auth::user()->type == 'editor' || Auth::user()->type == 'admin') {
                return $next($request);
            }else {
                return redirect()->route('/')->with('error', 'ONLY EDITORS OR ADMINS (MIDDLEWARE)');
            }
        }else {
            return redirect()->route('/')->with('error', 'PLEASE LOGGIN WITH AN EDITOR OR ADMIN ACCOUNT (MIDDLEWARE)');
        }
    }
}
