<?php

namespace App\Http\Middleware;
use Closure;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;


class HasStore
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
        if (Auth::check() && Auth::user()->store != null) {
            return $next($request);
        }
        else{
            if(Request::segment(1) == 'step1' || Request::segment(2) == 'step1'){
                return $next($request);
            }else{
                return redirect()->route('setup.step1');
            }
        }
    }
}
