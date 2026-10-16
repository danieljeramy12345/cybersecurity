<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SecurityAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(
        protected SecurityAuditService $auditService
    ) {}

    /**
     * Display the registration view.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-\.\'\"]+$/u'],
            'email' => ['required', 'string', 'email:rfc', 'max:191', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'name.regex' => 'The name field may only contain letters, spaces, hyphens, and apostrophes.',
            'password.min' => 'The password must be at least 8 characters long.',
        ]);

        $user = User::create([
            'name' => strip_tags(trim($validated['name'])),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'is_active' => true,
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Audit log registration security event
        $this->auditService->logEvent(
            eventType: 'auth.register',
            severity: 'low',
            description: sprintf('New account securely registered: "%s" (%s).', $user->name, $user->email),
            user: $user,
            request: $request,
            details: [
                'assigned_role' => 'user',
                'verification_status' => 'pending',
            ]
        );

        // Record successful initial login activity
        $this->auditService->recordLoginActivity(
            email: $user->email,
            status: 'success',
            user: $user,
            request: $request
        );

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Account registered successfully. Welcome to CyberShield!');
    }
}
