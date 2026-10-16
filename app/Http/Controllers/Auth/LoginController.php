<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SecurityAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        protected SecurityAuditService $auditService
    ) {}

    /**
     * Show the application login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = strtolower(trim($credentials['email']));
        $throttleKey = $this->throttleKey($request, $email);

        // 1. Enforce login rate limiting (max 5 attempts per 60 seconds)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            $this->auditService->logEvent(
                eventType: 'auth.login.rate_limited',
                severity: 'high',
                description: sprintf('Login temporarily throttled for %s from IP %s. Retry after %d seconds.', $email, $request->ip(), $seconds),
                user: null,
                request: $request,
                details: [
                    'target_email' => $email,
                    'seconds_remaining' => $seconds,
                ]
            );

            $this->auditService->recordLoginActivity(
                email: $email,
                status: 'blocked',
                user: null,
                request: $request,
                failureReason: 'rate_limited'
            );

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        $remember = $request->boolean('remember');

        // 2. Attempt authentication
        if (! Auth::attempt(['email' => $email, 'password' => $credentials['password']], $remember)) {
            RateLimiter::hit($throttleKey, 60);

            $this->auditService->recordLoginActivity(
                email: $email,
                status: 'failed',
                user: null,
                request: $request,
                failureReason: 'invalid_credentials'
            );

            $this->auditService->logEvent(
                eventType: 'auth.login.failed',
                severity: 'medium',
                description: sprintf('Failed login attempt for account "%s" from IP %s.', $email, $request->ip()),
                user: null,
                request: $request,
                details: [
                    'target_email' => $email,
                    'attempts_made' => RateLimiter::attempts($throttleKey),
                ]
            );

            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our security records.',
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        // 3. Verify that the account is active
        if (! $user->isActive()) {
            Auth::logout();
            RateLimiter::hit($throttleKey, 60);

            $this->auditService->recordLoginActivity(
                email: $email,
                status: 'blocked',
                user: $user,
                request: $request,
                failureReason: 'account_inactive'
            );

            $this->auditService->logEvent(
                eventType: 'auth.login.inactive_account_blocked',
                severity: 'high',
                description: sprintf('Deactivated account "%s" attempted login and was blocked.', $email),
                user: $user,
                request: $request
            );

            throw ValidationException::withMessages([
                'email' => 'Your account is currently deactivated. Please contact an authorized administrator.',
            ]);
        }

        // 4. Authentication success: clear rate limiting and regenerate session token
        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        // Update user's last login telemetry
        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        $this->auditService->recordLoginActivity(
            email: $email,
            status: 'success',
            user: $user,
            request: $request
        );

        $this->auditService->logEvent(
            eventType: 'auth.login.success',
            severity: 'low',
            description: sprintf('User "%s" (%s) authenticated successfully. Session regenerated.', $user->name, $user->email),
            user: $user,
            request: $request,
            details: [
                'role' => $user->role,
                'remember_me' => $remember,
            ]
        );

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Admin session initialized.');
        }

        return redirect()->intended(route('dashboard'))->with('success', 'Welcome back, ' . $user->name . '.');
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(Request $request, string $email): string
    {
        return Str::transliterate(Str::lower($email) . '|' . $request->ip());
    }
}
