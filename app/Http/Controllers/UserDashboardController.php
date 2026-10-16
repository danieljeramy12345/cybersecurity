<?php

namespace App\Http\Controllers;

use App\Models\LoginActivity;
use App\Models\SecurityEvent;
use App\Services\SecurityAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function __construct(
        protected SecurityAuditService $auditService
    ) {}

    /**
     * Display the user security dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Recent login activities for this user
        $recentLogins = LoginActivity::where('user_id', $user->id)
            ->latest('attempted_at')
            ->take(10)
            ->get();

        // Recent security notifications/events related to this user
        $securityEvents = SecurityEvent::where('user_id', $user->id)
            ->latest('occurred_at')
            ->take(8)
            ->get();

        // Calculate account security score
        $securityScore = 70; // baseline
        if ($user->email_verified_at) {
            $securityScore += 15;
        }
        if ($user->password && strlen($user->password) > 20) {
            $securityScore += 15;
        }

        return view('dashboard.user', compact('user', 'recentLogins', 'securityEvents', 'securityScore'));
    }

    /**
     * Update user profile information.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\-\.\'\"]+$/u'],
        ], [
            'name.regex' => 'The name may only contain letters, spaces, hyphens, and apostrophes.',
        ]);

        $oldName = $user->name;
        $user->name = strip_tags(trim($validated['name']));
        $user->save();

        $this->auditService->logEvent(
            eventType: 'account.profile_updated',
            severity: 'low',
            description: sprintf('User "%s" updated profile name (prior: "%s").', $user->email, $oldName),
            user: $user,
            request: $request
        );

        return back()->with('success', 'Profile information updated successfully.');
    }

    /**
     * Update user password with current password verification.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                'different:current_password',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'password.different' => 'Your new password cannot be the same as your current password.',
        ]);

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        $this->auditService->logEvent(
            eventType: 'account.password_changed',
            severity: 'medium',
            description: sprintf('Password updated successfully for account "%s".', $user->email),
            user: $user,
            request: $request
        );

        return back()->with('success', 'Your password has been changed securely.');
    }
}
