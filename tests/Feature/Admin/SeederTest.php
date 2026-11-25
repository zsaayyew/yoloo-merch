<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\OwnerUserSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_seeder_creates_owner_and_admin_roles(): void
    {
        $this->seed(RoleSeeder::class);

        $this->assertDatabaseHas('roles', ['name' => 'owner', 'label' => 'Owner']);
        $this->assertDatabaseHas('roles', ['name' => 'admin', 'label' => 'Admin']);
    }

    public function test_owner_seeder_creates_owner_account_from_config(): void
    {
        config([
            'admin.owner.name' => 'Owner YOLOO Merch',
            'admin.owner.email' => 'owner@yoloo.test',
            'admin.owner.password' => 'rahasia-owner',
        ]);

        $this->seed(RoleSeeder::class);
        $this->seed(OwnerUserSeeder::class);

        $owner = User::where('email', 'owner@yoloo.test')->firstOrFail();

        $this->assertTrue($owner->isOwner());
        $this->assertTrue($owner->is_active);
        $this->assertTrue(Hash::check('rahasia-owner', $owner->password));
    }

    public function test_owner_seeder_skips_when_credentials_are_missing(): void
    {
        config([
            'admin.owner.email' => null,
            'admin.owner.password' => null,
        ]);

        $this->seed(RoleSeeder::class);
        $this->seed(OwnerUserSeeder::class);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_admin_seeder_creates_admin_account_from_config(): void
    {
        config([
            'admin.admin.name' => 'Admin YOLOO Merch',
            'admin.admin.email' => 'admin@yoloo.test',
            'admin.admin.password' => 'rahasia-admin',
        ]);

        $this->seed(RoleSeeder::class);
        $this->seed(AdminUserSeeder::class);

        $admin = User::where('email', 'admin@yoloo.test')->firstOrFail();

        $this->assertTrue($admin->hasRole(Role::ADMIN));
        $this->assertFalse($admin->isOwner());
        $this->assertTrue($admin->is_active);
        $this->assertTrue(Hash::check('rahasia-admin', $admin->password));
    }

    public function test_admin_seeder_skips_when_credentials_are_missing(): void
    {
        config([
            'admin.admin.email' => null,
            'admin.admin.password' => null,
        ]);

        $this->seed(RoleSeeder::class);
        $this->seed(AdminUserSeeder::class);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_database_seeder_creates_both_owner_and_admin(): void
    {
        config([
            'admin.owner.email' => 'owner@yoloo.test',
            'admin.owner.password' => 'rahasia-owner',
            'admin.admin.email' => 'admin@yoloo.test',
            'admin.admin.password' => 'rahasia-admin',
        ]);

        $this->seed();

        $this->assertDatabaseCount('users', 2);
        $this->assertTrue(User::where('email', 'owner@yoloo.test')->firstOrFail()->isOwner());
        $this->assertTrue(User::where('email', 'admin@yoloo.test')->firstOrFail()->hasRole(Role::ADMIN));
    }
}
