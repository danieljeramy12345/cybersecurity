<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SecurityAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __construct(
        protected SecurityAuditService $auditService
    ) {}

    /**
     * Log the user out of the application, invalidate session, and regenerate CSRF token.
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            $this->auditService->logEvent(
                eventType: 'auth.logout',
                severity: 'low',
                description: sprintf('User "%s" (%s) logged out securely. Session destroyed.', $user->name, $user->email),
                user: $user,
                request: $request
            );
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been securely logged out.');
    }
}
