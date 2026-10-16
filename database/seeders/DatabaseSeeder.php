<?php

namespace Database\Seeders;

use App\Models\LoginActivity;
use App\Models\SecurityEvent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial accounts and baseline security logs.
     */
    public function run(): void
    {
        // 1. Initial Administrator Account
        $admin = User::firstOrCreate(
            ['email' => 'admin@cybershield.security'],
            [
                'name' => 'CyberShield Admin',
                'password' => Hash::make('AdminPassword123!#'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subHours(2),
                'last_login_ip' => '127.0.0.1',
            ]
        );

        // 2. Initial Standard User Account
        $user = User::firstOrCreate(
            ['email' => 'user@cybershield.security'],
            [
                'name' => 'Jeramy Analyst',
                'password' => Hash::make('UserPassword123!#'),
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subDay(),
                'last_login_ip' => '127.0.0.1',
            ]
        );

        // 3. Baseline Security Events
        SecurityEvent::create([
            'user_id' => $admin->id,
            'event_type' => 'system.init',
            'severity' => 'low',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'CyberShield Security Core / PHP 8.3 CLI',
            'description' => 'System security initialized and default security policies deployed.',
            'details' => ['encryption' => 'AES-256-CBC', 'hashing' => 'bcrypt', 'database' => 'MySQL 8.4'],
            'occurred_at' => now()->subDays(3),
        ]);

        SecurityEvent::create([
            'user_id' => $admin->id,
            'event_type' => 'auth.login.success',
            'severity' => 'low',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) CyberShield Client',
            'description' => 'Admin authentication succeeded with verified session token.',
            'details' => ['method' => 'web-session', 'role' => 'admin'],
            'occurred_at' => now()->subHours(2),
        ]);

        SecurityEvent::create([
            'user_id' => null,
            'event_type' => 'auth.login.failed',
            'severity' => 'medium',
            'ip_address' => '192.168.1.150',
            'user_agent' => 'Mozilla/5.0 (Unknown Automated Scanner)',
            'description' => 'Failed login attempt detected for unverified target (root@external.io).',
            'details' => ['reason' => 'invalid_credentials'],
            'occurred_at' => now()->subHours(1),
        ]);

        // 4. Baseline Login Activities
        LoginActivity::create([
            'user_id' => $admin->id,
            'email' => $admin->email,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'status' => 'success',
            'failure_reason' => null,
            'attempted_at' => now()->subHours(2),
        ]);

        LoginActivity::create([
            'user_id' => null,
            'email' => 'root@external.io',
            'ip_address' => '192.168.1.150',
            'user_agent' => 'Mozilla/5.0 (Unknown Automated Scanner)',
            'status' => 'failed',
            'failure_reason' => 'invalid_credentials',
            'attempted_at' => now()->subHours(1),
        ]);
    }
}
