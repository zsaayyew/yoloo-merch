<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }

    public function test_login_page_can_be_rendered(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('YOLOO Merch Admin');
    }

    public function test_owner_can_login_and_reach_dashboard(): void
    {
        $user = $this->makeUser(Role::OWNER);

        $response = $this->post(route('admin.login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->get(route('admin.dashboard'))->assertOk();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = $this->makeUser(Role::OWNER);

        $response = $this->from(route('admin.login'))->post(route('admin.login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('admin.login'))->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = $this->makeUser(Role::OWNER, isActive: false);

        $response = $this->from(route('admin.login'))->post(route('admin.login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.login'))->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = $this->makeUser(Role::ADMIN);

        $response = $this->actingAs($user)->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_active_middleware_logs_out_deactivated_user(): void
    {
        $user = $this->makeUser(Role::OWNER);
        $this->actingAs($user);
        $user->update(['is_active' => false]);

        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'))->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_role_middleware_allows_owner(): void
    {
        $this->registerOwnerOnlyRoute();

        $owner = $this->makeUser(Role::OWNER);

        $this->actingAs($owner)->get('/_test/owner-only')->assertOk()->assertSee('owner-only');
    }

    public function test_role_middleware_forbids_admin(): void
    {
        $this->registerOwnerOnlyRoute();

        $admin = $this->makeUser(Role::ADMIN);

        $this->actingAs($admin)->get('/_test/owner-only')->assertForbidden();
    }

    public function test_login_is_throttled_after_six_failed_attempts(): void
    {
        $user = $this->makeUser(Role::OWNER);

        for ($attempt = 1; $attempt <= 6; $attempt++) {
            $this->post(route('admin.login.store'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])->assertSessionHasErrors('email');
        }

        $this->post(route('admin.login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertStatus(429);
    }

    private function makeUser(string $roleName, bool $isActive = true): User
    {
        $role = Role::firstOrCreate(['name' => $roleName], ['label' => ucfirst($roleName)]);

        return User::factory()->create([
            'role_id' => $role->id,
            'is_active' => $isActive,
        ]);
    }

    private function registerOwnerOnlyRoute(): void
    {
        Route::middleware(['web', 'auth', 'active', 'role:owner'])
            ->get('/_test/owner-only', fn (): string => 'owner-only');
    }
}
