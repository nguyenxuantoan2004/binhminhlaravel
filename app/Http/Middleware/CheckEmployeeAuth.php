<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmployeeAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('web')->check()) {
            Auth::guard('web')->viaRemember();
        }

         // Nếu người dùng đã đăng nhập và đang truy cập route admin.login, chuyển hướng đến admin.index
        //  if (Auth::guard('web')->check() && $request->routeIs('admin.login')) {
        //     return redirect()->route('admin.index'); // Trang chính sau khi đăng nhập
        // }

        // Nếu chưa đăng nhập, chuyển hướng đến trang login
        if (!Auth::guard('web')->check() && !$request->routeIs('admin.login')) {
            return redirect()->route('admin.login');
        }

        return $next($request);
       
    }
}
