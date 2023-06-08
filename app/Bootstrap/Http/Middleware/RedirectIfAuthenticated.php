<?php

namespace App\Bootstrap\Http\Middleware;

use App\Bootstrap\Providers\RouteServiceProvider;
use App\Modules\Users\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = User::find(Auth::id());
                $device = $request->device_name ?? env('GENERIC_DEVICE_NAME');
                return response()->json([
                    'token' => $user->createToken($device)->plainTextToken,
                    200
                ]);
            }
        }

        return $next($request);
    }
}
