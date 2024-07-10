<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    protected $adminPermissions = [
        'product.add',
        'merchant.add',
    ];

    protected $userPermissions = [
        'order.create',
        'cart.manage',
        'wishlist.manage',
        'payment.process',
    ];

    public function run()
    {
        // Create permissions
        Permission::firstOrCreate(['name' => 'product.add', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'merchant.add', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'order.create', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'cart.manage', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'wishlist.manage', 'guard_name' => 'api']);
        Permission::firstOrCreate(['name' => 'payment.process', 'guard_name' => 'api']);

        // Assign permissions to roles
        $roles = Role::all();

        foreach ($roles as $role) {
            if ($role->name === 'admin') {
                $role->syncPermissions($this->adminPermissions);
            } elseif ($role->name === 'user') {
                $role->syncPermissions($this->userPermissions);
            }
        }
    }
}
