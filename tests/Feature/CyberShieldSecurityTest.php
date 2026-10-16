<?php

namespace Tests\Feature;

use App\Models\LoginActivity;
use App\Models\SecurityEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class CyberShieldSecurityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: User Registration with Validation and Security Logging.
     */
    public function test_user_can_register_with_valid_data_and_password_is_hashed(): void
    {
        $response = $this->post(route('register.post'), [
            'name' => 'John Defense',
            'email' => 'john@defense.security',
            'password' => 'SecurePass123!#',
            'password_confirmation' => 'SecurePass123!#',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'john@defense.security')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('SecurePass123!#', $user->password));
        $this->assertNotEquals('SecurePass123!#', $user->password);
        $this->assertEquals('user', $user->role);
        $this->assertTrue($user->is_active);

        // Security event recorded
        $this->assertDatabaseHas('security_events', [
            'user_id' => $user->id,
            'event_type' => 'auth.register',
        ]);
    }

    /**
     * Test 2: Duplicate email registration is prevented.
     */
    public function test_duplicate_email_registration_is_rejected(): void
    {
        User::factory()->create([
            'email' => 'existing@defense.security',
        ]);

        $response = $this->post(route('register.post'), [
            'name' => 'Duplicate Attempt',
            'email' => 'existing@defense.security',
            'password' => 'SecurePass123!#',
            'password_confirmation' => 'SecurePass123!#',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test 3: Successful login with session regeneration and telemetry tracking.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'analyst@defense.security',
            'password' => Hash::make('CorrectPassword123!#'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'analyst@defense.security',
            'password' => 'CorrectPassword123!#',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);

        // Verify login activity recorded
        $this->assertDatabaseHas('login_activities', [
            'user_id' => $user->id,
            'email' => 'analyst@defense.security',
            'status' => 'success',
        ]);

        // Verify security event logged
        $this->assertDatabaseHas('security_events', [
            'user_id' => $user->id,
            'event_type' => 'auth.login.success',
        ]);
    }

    /**
     * Test 4: Unsuccessful login records failure telemetry.
     */
    public function test_failed_login_records_activity_and_security_event(): void
    {
        $user = User::factory()->create([
            'email' => 'target@defense.security',
            'password' => Hash::make('ActualSecretPassword123!#'),
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'target@defense.security',
            'password' => 'WrongGuessPassword999',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->assertDatabaseHas('login_activities', [
            'email' => 'target@defense.security',
            'status' => 'failed',
            'failure_reason' => 'invalid_credentials',
        ]);

        $this->assertDatabaseHas('security_events', [
            'event_type' => 'auth.login.failed',
        ]);
    }

    /**
     * Test 5: Deactivated account is prevented from logging in.
     */
    public function test_deactivated_account_is_blocked_from_logging_in(): void
    {
        $user = User::factory()->create([
            'email' => 'locked@defense.security',
            'password' => Hash::make('ValidPassword123!#'),
            'is_active' => false,
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'locked@defense.security',
            'password' => 'ValidPassword123!#',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->assertDatabaseHas('login_activities', [
            'email' => 'locked@defense.security',
            'status' => 'blocked',
            'failure_reason' => 'account_inactive',
        ]);
    }

    /**
     * Test 6: Login rate limiting kicks in after 5 rapid failed attempts.
     */
    public function test_login_rate_limiting_throttles_rapid_failed_attempts(): void
    {
        $targetEmail = 'bruteforce@defense.security';

        // Perform 5 consecutive failed login attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.post'), [
                'email' => $targetEmail,
                'password' => 'BadPassword' . $i,
            ]);
        }

        // 6th attempt should be throttled
        $response = $this->post(route('login.post'), [
            'email' => $targetEmail,
            'password' => 'BadPasswordFinal',
        ]);

        $response->assertSessionHasErrors('email');

        // Verify rate limited security event logged
        $this->assertDatabaseHas('security_events', [
            'event_type' => 'auth.login.rate_limited',
            'severity' => 'high',
        ]);
    }

    /**
     * Test 7: Guest access restrictions for protected routes.
     */
    public function test_guests_cannot_access_protected_routes(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.security-monitoring'))->assertRedirect(route('login'));
    }

    /**
     * Test 8: Ordinary users cannot access administrator routes (RBAC).
     */
    public function test_regular_user_is_forbidden_from_admin_dashboard_and_logged(): void
    {
        $regularUser = User::factory()->create([
            'email' => 'user@regular.security',
            'role' => 'user',
            'is_active' => true,
        ]);

        $response = $this->actingAs($regularUser)->get(route('admin.dashboard'));
        $response->assertStatus(403);

        // Verify unauthorized access attempt security event recorded
        $this->assertDatabaseHas('security_events', [
            'user_id' => $regularUser->id,
            'event_type' => 'auth.unauthorized_admin_access',
            'severity' => 'high',
        ]);
    }

    /**
     * Test 9: Administrator can access admin routes and toggle user status.
     */
    public function test_admin_can_access_admin_dashboard_and_toggle_user_status(): void
    {
        $admin = User::factory()->create([
            'email' => 'soc.admin@defense.security',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $targetUser = User::factory()->create([
            'email' => 'target.user@defense.security',
            'role' => 'user',
            'is_active' => true,
        ]);

        // Admin access to dashboard
        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertStatus(200);

        // Admin access to security monitoring
        $this->actingAs($admin)
            ->get(route('admin.security-monitoring'))
            ->assertStatus(200);

        // Admin deactivates target user
        $toggleResponse = $this->actingAs($admin)
            ->post(route('admin.users.toggle-status', $targetUser->id));

        $toggleResponse->assertRedirect();
        $this->assertFalse($targetUser->fresh()->is_active);

        // Admin cannot deactivate self
        $selfToggleResponse = $this->actingAs($admin)
            ->post(route('admin.users.toggle-status', $admin->id));

        $selfToggleResponse->assertSessionHas('error');
        $this->assertTrue($admin->fresh()->is_active);
    }

    /**
     * Test 10: User profile update and password change with current password verification.
     */
    public function test_user_can_update_profile_and_change_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'personnel@defense.security',
            'password' => Hash::make('CurrentSecret123!#'),
            'role' => 'user',
            'is_active' => true,
        ]);

        // Update profile
        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'Updated Cyber Handle',
            ])
            ->assertSessionHas('success');

        $this->assertEquals('Updated Cyber Handle', $user->fresh()->name);

        // Update password with valid current password
        $this->actingAs($user)
            ->put(route('password.change'), [
                'current_password' => 'CurrentSecret123!#',
                'password' => 'NewStrongPassword456!#',
                'password_confirmation' => 'NewStrongPassword456!#',
            ])
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('NewStrongPassword456!#', $user->fresh()->password));

        // Attempting to update password with incorrect current password fails
        $this->actingAs($user)
            ->put(route('password.change'), [
                'current_password' => 'WrongCurrentPassword!',
                'password' => 'AnotherPassword789!#',
                'password_confirmation' => 'AnotherPassword789!#',
            ])
            ->assertSessionHasErrors('current_password');
    }

    /**
     * Test 11: Secure logout invalidates session.
     */
    public function test_user_can_logout_securely(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('home'));
        $this->assertGuest();
        $this->assertDatabaseHas('security_events', [
            'user_id' => $user->id,
            'event_type' => 'auth.logout',
        ]);
    }

    /**
     * Test 12: Password reset request and token verification.
     */
    public function test_password_reset_link_dispatch_and_reset_execution(): void
    {
        $user = User::factory()->create([
            'email' => 'recover@defense.security',
            'password' => Hash::make('OldPassword123!#'),
            'is_active' => true,
        ]);

        // Request reset link
        $linkResponse = $this->post(route('password.email'), [
            'email' => 'recover@defense.security',
        ]);
        $linkResponse->assertSessionHas('status');

        $this->assertDatabaseHas('security_events', [
            'event_type' => 'auth.password_reset_requested',
        ]);

        // Generate token and perform reset
        $token = Password::createToken($user);

        $resetResponse = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'recover@defense.security',
            'password' => 'BrandNewPassword123!#',
            'password_confirmation' => 'BrandNewPassword123!#',
        ]);

        $resetResponse->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('BrandNewPassword123!#', $user->fresh()->password));

        $this->assertDatabaseHas('security_events', [
            'user_id' => $user->id,
            'event_type' => 'auth.password_reset_success',
        ]);
    }
}
