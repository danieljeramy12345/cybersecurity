<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use App\Models\SecurityEvent;
use App\Services\SecurityAuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SecurityMonitoringController extends Controller
{
    public function __construct(
        protected SecurityAuditService $auditService
    ) {}

    /**
     * Display the security telemetry and threat monitoring console.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->input('status');
        $severityFilter = $request->input('severity');
        $eventTypeFilter = $request->input('event_type');

        // 1. Query Login Activities
        $loginActivitiesQuery = LoginActivity::with('user')->latest('attempted_at');
        if ($statusFilter && in_array($statusFilter, ['success', 'failed', 'blocked'])) {
            $loginActivitiesQuery->where('status', $statusFilter);
        }
        $loginActivities = $loginActivitiesQuery->paginate(15, ['*'], 'login_page')->withQueryString();

        // 2. Query Security Audit Events
        $securityEventsQuery = SecurityEvent::with('user')->latest('occurred_at');
        if ($severityFilter && in_array($severityFilter, ['low', 'medium', 'high', 'critical'])) {
            $securityEventsQuery->where('severity', $severityFilter);
        }
        if ($eventTypeFilter) {
            $securityEventsQuery->where('event_type', 'like', "%{$eventTypeFilter}%");
        }
        $securityEvents = $securityEventsQuery->paginate(15, ['*'], 'event_page')->withQueryString();

        // 3. Repeated Failed Logins Detection (Brute Force Watchlist)
        // Group failed attempts in the last 24 hours by target email or IP address
        $suspiciousIPs = LoginActivity::select('ip_address', DB::raw('count(*) as failure_count'))
            ->whereIn('status', ['failed', 'blocked'])
            ->where('attempted_at', '>=', now()->subHours(24))
            ->groupBy('ip_address')
            ->having('failure_count', '>=', 3)
            ->orderByDesc('failure_count')
            ->get();

        $suspiciousEmails = LoginActivity::select('email', DB::raw('count(*) as failure_count'))
            ->whereIn('status', ['failed', 'blocked'])
            ->where('attempted_at', '>=', now()->subHours(24))
            ->groupBy('email')
            ->having('failure_count', '>=', 3)
            ->orderByDesc('failure_count')
            ->get();

        // 4. Aggregate telemetry stats
        $stats = [
            'total_logins_24h' => LoginActivity::where('attempted_at', '>=', now()->subHours(24))->count(),
            'successful_24h' => LoginActivity::where('status', 'success')->where('attempted_at', '>=', now()->subHours(24))->count(),
            'failed_24h' => LoginActivity::where('status', 'failed')->where('attempted_at', '>=', now()->subHours(24))->count(),
            'blocked_24h' => LoginActivity::where('status', 'blocked')->where('attempted_at', '>=', now()->subHours(24))->count(),
            'critical_events_count' => SecurityEvent::where('severity', 'critical')->count(),
            'high_events_count' => SecurityEvent::where('severity', 'high')->count(),
        ];

        return view('admin.security-monitoring', compact(
            'loginActivities',
            'securityEvents',
            'suspiciousIPs',
            'suspiciousEmails',
            'stats',
            'statusFilter',
            'severityFilter',
            'eventTypeFilter'
        ));
    }
}
