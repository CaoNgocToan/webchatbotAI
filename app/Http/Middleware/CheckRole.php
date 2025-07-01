<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles): Response
    {
        // Để tạm thời, khi chưa phân quyền
        // return $next($request);
        // ========================================
        $hasRole = false;
        foreach ($roles as $role) {
            if (in_array($role, Auth::user()->roles)) {
                $hasRole = true;
                break;
            }
        }
        if ($hasRole)
            return $next($request);
        return redirect()->intended('not-permissions');
    }
}
