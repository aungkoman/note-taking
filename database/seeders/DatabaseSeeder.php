<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder

{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'admin@gmail.com',
        //     'password' => Hash::make('123456'),
        // ]);
        $this->call([
            RoleSeeder::class,
<<<<<<< HEAD
            UserSeeder::class,
=======
            UserSeeder::class
>>>>>>> 457bc200418e30a684f9137ea10d4e8b8e8f2e66
        ]);
    }
}
