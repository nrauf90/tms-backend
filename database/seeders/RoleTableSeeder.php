<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create roles with labels
        $roles = [
            [
                'name' => 'admin',
                'label' => 'Administrator',
                'guard_name' => 'web'
            ],
            [
                'name' => 'manager',
                'label' => 'Project Manager',
                'guard_name' => 'web'
            ],
            [
                'name' => 'supervisor',
                'label' => 'Team Supervisor',
                'guard_name' => 'web'
            ],
            [
                'name' => 'user',
                'label' => 'Regular User',
                'guard_name' => 'web'
            ],
            [
                'name' => 'client',
                'label' => 'Client User',
                'guard_name' => 'web'
            ]
        ];

        foreach ($roles as $roleData) {
            Role::create($roleData);
        }

        // Get all permissions
        $permissions = Permission::all();

        // Assign all permissions to admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions($permissions);
        }

        // Assign specific permissions to manager role
        $managerRole = Role::where('name', 'manager')->first();
        if ($managerRole) {
            $managerPermissions = Permission::whereIn('name', [
                'view-users',
                'create-users',
                'edit-users',
                'delete-users',
                'view-reports',
                'create-reports',
                'edit-reports',
                'delete-reports',
                'manage-team'
            ])->get();
            $managerRole->syncPermissions($managerPermissions);
        }

        // Assign basic permissions to user role
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $userPermissions = Permission::whereIn('name', [
                'view-profile',
                'edit-profile',
                'view-reports',
                'create-reports'
            ])->get();
            $userRole->syncPermissions($userPermissions);
        }
    }
}
