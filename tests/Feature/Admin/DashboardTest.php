<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_sees_welcome_box_and_summary_cards(): void
    {
        $owner = $this->makeUser(Role::OWNER);

        $this->actingAs($owner)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Selamat datang, '.$owner->name)
            ->assertSee('Ringkasan Data')
            ->assertSee('Produk')
            ->assertSee('Akun Admin')
            ->assertSee('Pengguna');
    }

    public function test_summary_counts_follow_database_content(): void
    {
        $categoryId = DB::table('categories')->insertGetId([
            'name' => 'Kaos',
            'slug' => 'kaos',
            'is_active' => true,
        ]);

        DB::table('products')->insert([
            ['category_id' => $categoryId, 'name' => 'Kaos Polos', 'slug' => 'kaos-polos', 'status' => 'active'],
            ['category_id' => $categoryId, 'name' => 'Kaos Sablon', 'slug' => 'kaos-sablon', 'status' => 'draft'],
        ]);

        $this->actingAs($this->makeUser(Role::OWNER))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('1 aktif · 1 draft · 0 arsip')
            ->assertSee('1 aktif · 0 nonaktif');
    }

    public function test_admin_does_not_get_owner_only_summary_and_menu(): void
    {
        $this->actingAs($this->makeUser(Role::ADMIN))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('Akun Admin')
            ->assertDontSee('admin/users');
    }

    public function test_admin_cannot_open_user_management(): void
    {
        $this->actingAs($this->makeUser(Role::ADMIN))
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_every_adm_menu_route_is_reachable(): void
    {
        $owner = $this->makeUser(Role::OWNER);

        $routes = [
            'admin.categories.index',
            'admin.products.index',
            'admin.pricing.index',
            'admin.media.index',
            'admin.content.homepage.edit',
            'admin.content.about.edit',
            'admin.content.settings.edit',
            'admin.content.testimonials.index',
            'admin.content.collaborations.index',
            'admin.content.gallery.index',
            'admin.content.gallery-categories.index',
            'admin.users.index',
        ];

        foreach ($routes as $route) {
            $this->actingAs($owner)->get(route($route))->assertOk();
        }
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
