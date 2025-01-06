<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsDoctor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // تحقق إذا كان المستخدم مسجل دخول ولديه  " role => doctor"
        if (auth('api')->check() && auth('api')->user()->role === 'doctor') {
            return $next($request);
        }
        // إذا لم يكن المستخدم اداري، ابعث خطأ
        return response()->json(['message'=> 'Only doctors can access this route.'], 403);
    }
}
