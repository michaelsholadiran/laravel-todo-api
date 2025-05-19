<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthorized. Token is required.'], 401);
        }

        $user = User::where('token', $token)->first();

        if (!$user) {
            $user = User::create([
                'token' => $token,
            ]);
        }

        $request->merge(['user' => $user]);

        return $next($request);
    }
} 