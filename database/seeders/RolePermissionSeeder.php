<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       /* $roles = [
            'specialist',
        ];

        foreach ($roles as $role){
            Role::create(['name' => $role]);
        }*/

        $role = Role::create(['name' => 'specialist']);

        $permissions = [
            'add menu',
            'edit menu',
            'delete menu',
            'add product',
            'edit product',
            'delete product',
        ];
        foreach ($permissions as $permission){
            Permission::create(['name' => $permission]);
        }
        $role->syncPermissions($permissions);
    }
}
