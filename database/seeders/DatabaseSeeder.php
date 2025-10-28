<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate([
            'email' => 'admin@refeitore.com'
        ], [
            'name' => 'Administrador',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);
    }
}
