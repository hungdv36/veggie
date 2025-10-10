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
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();

        if (!$user || !$user->role || !$user->role->permissions->pluck('name')->contains($permission)) {
            abort(403, 'Bạn không có quyền truy cập');
        }

        return $next($request);
    }
}
