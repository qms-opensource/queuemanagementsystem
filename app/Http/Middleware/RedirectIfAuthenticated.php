<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
                    $id = \Auth::user()->id;
                    $user= User::where('id',$id)->first();
                    $usertype = $user->type;
                    if($usertype == 0)
                        return redirect('/manage-patient/dashboardData/all');
                    else
                        return redirect('/manage-doctor/patient-appointment');
        }
		$user = Auth::user();
		return $next($request);
    }
}
