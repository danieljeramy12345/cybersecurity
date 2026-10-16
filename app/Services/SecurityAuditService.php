<?php

namespace App\Services;

use App\Models\LoginActivity;
use App\Models\SecurityEvent;
use App\Models\User;
use Illuminate\Http\Request;

class SecurityAuditService
{
    /**
     * Threshold for detecting brute force alerts.
     */
    public const BRUTE_FORCE_THRESHOLD = 3;
    public const BRUTE_FORCE_WINDOW_MINUTES = 15;

    /**
     * Log a security event to the audit trail.
     */
    public function logEvent(
        string $eventType,
        string $severity,
        string $description,
        ?User $user = null,
        ?Request $request = null,
        array $details = []
    ): SecurityEvent {
        // Redact any potentially sensitive keys from details defensively
        $sanitizedDetails = $this->sanitizeDetails($details);

        $ip = $request ? $request->ip() : request()->ip();
        $userAgent = $request ? $request->userAgent() : request()->userAgent();

        return SecurityEvent::create([
            'user_id' => $user?->id,
            'event_type' => $eventType,
            'severity' => in_array($severity, ['low', 'medium', 'high', 'critical']) ? $severity : 'low',
            'ip_address' => $ip,
            'user_agent' => substr((string) $userAgent, 0, 500),
            'description' => $description,
            'details' => $sanitizedDetails,
            'occurred_at' => now(),
        ]);
    }

    /**
     * Record an authentication attempt (login activity).
     */
    public function recordLoginActivity(
        string $email,
        string $status,
        ?User $user = null,
        ?Request $request = null,
        ?string $failureReason = null
    ): LoginActivity {
        $ip = $request ? $request->ip() : request()->ip();
        $userAgent = $request ? $request->userAgent() : request()->userAgent();

        $activity = LoginActivity::create([
            'user_id' => $user?->id,
            'email' => strtolower(trim($email)),
            'ip_address' => $ip,
            'user_agent' => substr((string) $userAgent, 0, 500),
            'status' => in_array($status, ['success', 'failed', 'blocked']) ? $status : 'failed',
            'failure_reason' => $failureReason,
            'attempted_at' => now(),
        ]);

        // If login failed or blocked, evaluate potential brute force activity
        if (in_array($status, ['failed', 'blocked'])) {
            $this->evaluateBruteForceRisk($email, $ip, $request);
        }

        return $activity;
    }

    /**
     * Evaluate recent failed attempts and trigger security alerts if brute force patterns emerge.
     */
    public function evaluateBruteForceRisk(string $email, string $ip, ?Request $request = null): void
    {
        $windowStart = now()->subMinutes(self::BRUTE_FORCE_WINDOW_MINUTES);

        $recentFailures = LoginActivity::where(function ($q) use ($email, $ip) {
            $q->where('email', strtolower(trim($email)))
              ->orWhere('ip_address', $ip);
        })
        ->whereIn('status', ['failed', 'blocked'])
        ->where('attempted_at', '>=', $windowStart)
        ->count();

        if ($recentFailures >= self::BRUTE_FORCE_THRESHOLD) {
            $severity = $recentFailures >= (self::BRUTE_FORCE_THRESHOLD * 2) ? 'critical' : 'high';

            $this->logEvent(
                eventType: 'threat.brute_force_detected',
                severity: $severity,
                description: sprintf(
                    'Potential brute-force attack detected: %d failed login attempts within %d minutes targeting %s from IP %s.',
                    $recentFailures,
                    self::BRUTE_FORCE_WINDOW_MINUTES,
                    $email,
                    $ip
                ),
                user: null,
                request: $request,
                details: [
                    'failed_attempts' => $recentFailures,
                    'target_email' => $email,
                    'window_minutes' => self::BRUTE_FORCE_WINDOW_MINUTES,
                    'action_taken' => 'monitored_and_throttled',
                ]
            );
        }
    }

    /**
     * Defense-in-depth: Recursively sanitize and redact sensitive fields from logged details.
     */
    protected function sanitizeDetails(array $data): array
    {
        $sensitiveKeys = ['password', 'password_confirmation', 'token', 'secret', 'api_key', 'cookie', 'authorization'];

        $sanitized = [];
        foreach ($data as $key => $value) {
            if (in_array(strtolower($key), $sensitiveKeys, true)) {
                $sanitized[$key] = '[REDACTED_SECURITY_POLICY]';
            } elseif (is_array($value)) {
                $sanitized[$key] = $this->sanitizeDetails($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }
}
