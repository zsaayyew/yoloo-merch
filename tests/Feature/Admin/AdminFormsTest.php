<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFormsTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_create_form_renders_for_owner(): void
    {
        $owner = $this->makeUser(Role::OWNER);
        $product = $this->makeProduct();

        $routes = [
            'admin.categories.create',
            'admin.products.create',
            'admin.users.create',
            'admin.content.testimonials.create',
            'admin.content.collaborations.create',
            'admin.content.gallery.create',
            'admin.content.gallery-categories.create',
        ];

        foreach ($routes as $route) {
            $this->actingAs($owner)->get(route($route))->assertOk();
        }

        $this->actingAs($owner)->get(route('admin.pricing.create', $product))->assertOk();
        $this->actingAs($owner)->get(route('admin.products.options.index', $product))->assertOk();
    }

    public function test_edit_forms_render_for_owner(): void
    {
        $owner = $this->makeUser(Role::OWNER);
        $product = $this->makeProduct();

        $category = Category::query()->firstOrFail();
        $pricing = $product->pricingConfigurations()->create([
            'price_model' => 'fixed',
            'status' => 'active',
            'effective_date' => '2026-11-01',
            'version' => 1,
            'base_price' => 65000,
            'config' => ['minimum_quantity' => 1],
        ]);

        $this->actingAs($owner)->get(route('admin.products.edit', $product))->assertOk();
        $this->actingAs($owner)->get(route('admin.categories.edit', $category))->assertOk();
        $this->actingAs($owner)->get(route('admin.users.edit', $owner))->assertOk();
        $this->actingAs($owner)->get(route('admin.pricing.edit', $pricing))->assertOk();
    }

    private function makeProduct(): Product
    {
        $category = Category::query()->create([
            'name' => 'Kaos',
            'slug' => 'kaos',
            'is_active' => true,
        ]);

        return Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Kaos Custom',
            'slug' => 'kaos-custom',
            'price_model' => 'calculator',
            'status' => Product::STATUS_ACTIVE,
        ]);
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
