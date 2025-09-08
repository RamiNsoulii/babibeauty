<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{

    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // ✅ Admin bypass: give full access
        if (strtolower($user->role) === 'admin') {
            return $next($request);
        }

        // flatten roles (sometimes Laravel passes "admin,expert" as single param)
        $allowed = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $seg) {
                $seg = trim($seg);
                if ($seg !== '') $allowed[] = strtolower($seg);
            }
        }

        if (empty($allowed)) {
            // no role param provided — allow by default (or deny if you prefer)
            return $next($request);
        }

        if (!in_array(strtolower($user->role), $allowed, true)) {
            return response()->json(['message' => 'Forbidden. You do not have the required role.'], 403);
        }

        return $next($request);
    }
}

