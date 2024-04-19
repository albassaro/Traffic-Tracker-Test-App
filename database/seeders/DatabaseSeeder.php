<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AdminUser;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        AdminUser::factory(1)->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('admin')
        ]);

        Role::create([
            'name' => 'webmaster'
        ]);
        Role::create([
            'name' => 'advertiser'
        ]);
        
    }
}
