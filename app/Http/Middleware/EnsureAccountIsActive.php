<?php

namespace App\Http\Middleware;

use App\Services\SecurityAuditService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    public function __construct(
        protected SecurityAuditService $auditService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->isActive()) {
            $this->auditService->logEvent(
                eventType: 'auth.deactivated_session_terminated',
                severity: 'medium',
                description: sprintf('Deactivated account "%s" attempted action; session terminated immediately.', $user->email),
                user: $user,
                request: $request
            );

            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been deactivated by a security administrator.',
            ]);
        }

        return $next($request);
    }
}
