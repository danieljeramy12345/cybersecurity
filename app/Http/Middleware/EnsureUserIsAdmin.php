<?php

namespace App\Http\Middleware;

use App\Services\SecurityAuditService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
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

        if (! $user) {
            return redirect()->guest(route('login'))->with('error', 'Authentication required to access administrator features.');
        }

        if (! $user->isAdmin()) {
            // Log unauthorized privilege escalation attempt
            $this->auditService->logEvent(
                eventType: 'auth.unauthorized_admin_access',
                severity: 'high',
                description: sprintf('Unauthorized user "%s" (%s) attempted to access protected admin endpoint: %s', $user->name, $user->email, $request->path()),
                user: $user,
                request: $request,
                details: [
                    'attempted_url' => $request->fullUrl(),
                    'user_role' => $user->role,
                ]
            );

            abort(403, 'Access denied. You do not possess administrator security privileges.');
        }

        return $next($request);
    }
}
