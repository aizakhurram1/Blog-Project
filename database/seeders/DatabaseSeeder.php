<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Post::factory(50)->create();
        /** @var \App\Models\User $admin_user */
        $admin_user = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'name' => 'admin',
            'password' => bcrypt('admin123'),

        ]);

        $admin_role = Role::create(['name' => 'admin']);
        $admin_user->assignRole($admin_role);
    }
}
