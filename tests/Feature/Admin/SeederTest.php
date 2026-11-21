<?php

namespace Tests\Feature\Admin;

use App\Models\User;
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
}
