<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class CheckRememberToken
{
    public function handle(Request $request, Closure $next)
    {
        $user_id = $request->route('user_id');
        $user = User::find($user_id);

        if (!$user || !$user->remember_token) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return $next($request);
    }
} 