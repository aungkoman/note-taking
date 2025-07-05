<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $now=Carboon::now();
        Role::insert([
            ['id'=>1,'name'=>'Admin','created_at'=>now(),'updated_at'=>now()],
            ['id'=>2,'name'=>'user','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
