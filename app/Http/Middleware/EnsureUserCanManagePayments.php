<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanManagePayments
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->canManagePayments()) {
            abort(403, 'Alleen instructeurs en administrators hebben toegang tot betalingen.');
        }

        return $next($request);
    }
}
