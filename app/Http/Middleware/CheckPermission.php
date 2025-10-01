<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next, $permission): Response
    // {
    //     $user = Auth::guard('admin')->user();

    //     if (!$user || !$user->role->permissions->contains('name', $permission)) {
    //         abort(403, 'Bạn không có quyền truy cập');
    //     }
    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next, $permission = null): Response
    {
        // 🚨 Tạm bỏ qua check quyền + đăng nhập
        return $next($request);
    }
}
