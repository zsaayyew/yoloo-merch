<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate(['name' => Role::OWNER], ['label' => 'Owner']);
        Role::updateOrCreate(['name' => Role::ADMIN], ['label' => 'Admin']);
    }
}
