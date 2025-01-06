<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{

    public function handle(Request $request, Closure $next): Response
    {
        // تحقق إذا كان المستخدم مسجل دخول ولديه  " role => admin"
        if (auth('api')->check() && auth('api')->user()->role === 'admin') {
            return $next($request);
        }

        // إذا لم يكن المستخدم اداري، ابعث خطأ
        return response()->json(['message'=> 'Only admins can access this route.'], 403);

    }
}
