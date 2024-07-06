<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telp' => 'random',
            'password' => bcrypt('admin'),
            'role' => 'admin'
        ]);
        $admin->assignRole('admin');
        // Assign roles and permissions
        $admin->syncRoles(['admin']);
        $admin->syncPermissions(['create-post', 'edit-post', 'delete-post']);
    }
}
