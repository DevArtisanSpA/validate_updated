<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\UserType;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    //public function handle($request, Closure $next, $table, $permission)
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            if(Auth::user()->user_type_id > 1){
                return redirect()->intended('');
            }
             return $next($request);
        }
        else{
            return redirect()->intended('');
        }
    }
}
