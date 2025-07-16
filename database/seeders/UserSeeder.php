<?php

namespace Database\Seeders;

<<<<<<< HEAD
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
=======
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
>>>>>>> 457bc200418e30a684f9137ea10d4e8b8e8f2e66

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
<<<<<<< HEAD
     public function run(): void
    {
        $adminRoleId = DB::table('roles')->where('name', 'Admin')->value('id');
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'), // use a secure password
            'role_id' =>  $adminRoleId, // make sure role with ID 1 exists
            'email_verified_at' => now(),
        ]);
=======
    public function run(): void
    {
        User::create([
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role_id' => 1 // Admin Role
            ]);
>>>>>>> 457bc200418e30a684f9137ea10d4e8b8e8f2e66
    }
}
