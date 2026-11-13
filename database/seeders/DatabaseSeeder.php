<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password) {
            $this->command?->warn('ADMIN_EMAIL et ADMIN_PASSWORD doivent être définis dans .env.');

            return;
        }

        $admin = User::firstOrNew(['email' => $email]);
        $admin->name = $admin->name ?: 'Administrateur';
        $admin->password = $password;
        $admin->is_admin = true;
        $admin->save();
    }
}