<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = config('admin.admin.email');
        $password = config('admin.admin.password');

        if (blank($email) || blank($password)) {
            $this->command?->warn('ADMIN_EMAIL dan ADMIN_PASSWORD belum diisi di .env, akun Admin dilewati.');

            return;
        }

        $role = Role::firstOrCreate(['name' => Role::ADMIN], ['label' => 'Admin']);

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => config('admin.admin.name'),
                'password' => $password,
                'role_id' => $role->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );
    }
}
