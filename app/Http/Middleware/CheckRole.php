<?php

namespace App\Http\Middleware;

use App\Helpers\Constant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $roles = "")
    {
        $status = array_keys(Constant::STATUS_ADMIN);
        if (!empty($roles)) {
            if (Auth::check() && Auth::user()->status == $status[0]) {
                $allowedRoles = explode(' ', $roles);
                if (in_array(Auth::user()->role, $allowedRoles)) {
                    return $next($request);
                }
            }
        } else {
            if (Auth::check() && Auth::user()->status == $status[0]) {
                return $next($request);
            }
            Auth::logout();
            return redirect()->route('login')->with('error', 'Không được phép thực hiện tiếp hành động vì bạn chưa được người quản trị cấp quyền!');
        }

        return back()->with('statusFail', "Không được phép thực hiện tiếp hành động vì bạn chưa được người quản trị cấp quyền!");
    }
}