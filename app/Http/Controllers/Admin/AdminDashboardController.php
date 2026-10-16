<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use App\Models\SecurityEvent;
use App\Models\User;
use App\Services\SecurityAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected SecurityAuditService $auditService
    ) {}

    /**
     * Display the administrator overview dashboard.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');
        $statusFilter = $request->input('status');

        $usersQuery = User::query()->latest();

        if ($search) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleFilter && in_array($roleFilter, ['admin', 'user'])) {
            $usersQuery->where('role', $roleFilter);
        }

        if ($statusFilter !== null && in_array($statusFilter, ['active', 'inactive'])) {
            $usersQuery->where('is_active', $statusFilter === 'active');
        }

        $users = $usersQuery->paginate(10)->withQueryString();

        // Metrics
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $deactivatedUsers = User::where('is_active', false)->count();
        $failedLoginsToday = LoginActivity::where('status', 'failed')
            ->where('attempted_at', '>=', now()->startOfDay())
            ->count();
        $criticalEvents = SecurityEvent::whereIn('severity', ['high', 'critical'])->count();

        // Recent login activities
        $recentLogins = LoginActivity::with('user')
            ->latest('attempted_at')
            ->take(8)
            ->get();

        // Recent security audit events
        $recentEvents = SecurityEvent::with('user')
            ->latest('occurred_at')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'deactivatedUsers',
            'failedLoginsToday',
            'criticalEvents',
            'recentLogins',
            'recentEvents',
            'search',
            'roleFilter',
            'statusFilter'
        ));
    }

    /**
     * Toggle a user's active/deactivated status.
     */
    public function toggleUserStatus(Request $request, User $user): RedirectResponse
    {
        $admin = $request->user();

        // Prevent admin from deactivating themselves (prevent lockout)
        if ($admin->id === $user->id) {
            return back()->with('error', 'Security rule violation: Administrators cannot deactivate their own account.');
        }

        $newStatus = ! $user->is_active;
        $user->is_active = $newStatus;
        $user->save();

        $actionWord = $newStatus ? 'activated' : 'deactivated';
        $severity = $newStatus ? 'low' : 'medium';

        $this->auditService->logEvent(
            eventType: 'admin.user_status_changed',
            severity: $severity,
            description: sprintf('Admin "%s" %s user account "%s" (%s).', $admin->email, $actionWord, $user->name, $user->email),
            user: $admin,
            request: $request,
            details: [
                'target_user_id' => $user->id,
                'target_email' => $user->email,
                'new_status' => $newStatus ? 'active' : 'deactivated',
            ]
        );

        return back()->with('success', sprintf('Account for %s has been %s.', $user->name, $actionWord));
    }
}
