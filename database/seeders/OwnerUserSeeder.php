<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class OwnerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = config('admin.owner.email');
        $password = config('admin.owner.password');

        if (blank($email) || blank($password)) {
            $this->command?->warn('OWNER_EMAIL dan OWNER_PASSWORD belum diisi di .env, akun Owner dilewati.');

            return;
        }

        $role = Role::firstOrCreate(['name' => Role::OWNER], ['label' => 'Owner']);

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => config('admin.owner.name'),
                'password' => $password,
                'role_id' => $role->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );
    }
}
