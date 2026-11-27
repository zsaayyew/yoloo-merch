<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_open_user_management(): void
    {
        $admin = $this->makeUser(Role::ADMIN);

        $this->actingAs($admin)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.users.create'))->assertForbidden();
    }

    public function test_owner_can_create_and_update_an_admin_account(): void
    {
        $owner = $this->makeUser(Role::OWNER);
        $adminRole = Role::firstOrCreate(['name' => Role::ADMIN], ['label' => 'Admin']);

        $this->actingAs($owner)
            ->post(route('admin.users.store'), [
                'name' => 'Admin Baru',
                'email' => 'admin.baru@yoloo.test',
                'role_id' => $adminRole->id,
                'password' => 'rahasia12345',
                'password_confirmation' => 'rahasia12345',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.users.index'));

        $created = User::query()->where('email', 'admin.baru@yoloo.test')->firstOrFail();
        $this->assertTrue($created->is_active);
        $this->assertSame($adminRole->id, $created->role_id);

        $this->actingAs($owner)
            ->put(route('admin.users.update', $created), [
                'name' => 'Admin Diperbarui',
                'email' => 'admin.baru@yoloo.test',
                'role_id' => $adminRole->id,
                'is_active' => '0',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertSame('Admin Diperbarui', $created->fresh()->name);
        $this->assertFalse($created->fresh()->is_active);
    }

    public function test_owner_account_cannot_be_demoted_or_deleted(): void
    {
        $owner = $this->makeUser(Role::OWNER);
        $adminRole = Role::firstOrCreate(['name' => Role::ADMIN], ['label' => 'Admin']);

        $this->actingAs($owner)
            ->put(route('admin.users.update', $owner), [
                'name' => $owner->name,
                'email' => $owner->email,
                'role_id' => $adminRole->id,
                'is_active' => '1',
            ])
            ->assertSessionHasErrors('role_id');

        $this->assertTrue($owner->fresh()->isOwner());

        $this->actingAs($owner)
            ->delete(route('admin.users.destroy', $owner))
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $owner->id]);
    }

    public function test_owner_cannot_deactivate_their_own_account(): void
    {
        $owner = $this->makeUser(Role::OWNER);
        $ownerRole = Role::firstOrCreate(['name' => Role::OWNER], ['label' => 'Owner']);

        $this->actingAs($owner)
            ->put(route('admin.users.update', $owner), [
                'name' => $owner->name,
                'email' => $owner->email,
                'role_id' => $ownerRole->id,
                'is_active' => '0',
            ])
            ->assertSessionHasErrors('is_active');

        $this->assertTrue($owner->fresh()->is_active);
    }

    private function makeUser(string $roleName): User
    {
        $role = Role::firstOrCreate(['name' => $roleName], ['label' => ucfirst($roleName)]);

        return User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }
}
