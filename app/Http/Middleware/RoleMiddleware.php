<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('warning', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $user = Auth::user();

        if (! in_array($user->role, $roles)) {
            abort(403, 'Bạn không có quyền truy cập vào chức năng này.');
        }

        return $next($request);
    }
}
