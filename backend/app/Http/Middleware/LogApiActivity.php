<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;

class LogApiActivity
{
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        if ($request->user()) {
            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => $request->route()?->getName() ?? 'api_action',
                'method' => $request->method(),
                'endpoint' => $request->path(),
                'ip_address' => $request->ip(),
                'payload' => $request->except(['password', 'password_confirmation']),
            ]);
        }

        return $response;
    }
}
