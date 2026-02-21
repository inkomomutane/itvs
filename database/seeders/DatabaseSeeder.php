<?php

namespace Database\Seeders;

use App\Models\Role;
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

        $user = User::updateOrCreate([
            'sap_number' => '12345'
        ], [
            'name' => 'Administrador',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);

        Role::updateOrCreate(['name' => 'chef'],['name' => 'chef']);
        Role::updateOrCreate(['name' => 'employee'],['name' => 'employee']);


        $user->roles()->sync(['chef','employee']);





    }
}
