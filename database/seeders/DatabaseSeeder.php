<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create test users
        $users = [
            [
                'username' => 'john.doe',
                'fname' => 'John',
                'lname' => 'Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
                'status' => 1
            ],
            [
                'username' => 'jane.smith',
                'fname' => 'Jane',
                'lname' => 'Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password123'),
                'status' => 1
            ],
            [
                'username' => 'manager',
                'fname' => 'Project',
                'lname' => 'Manager',
                'email' => 'manager@example.com',
                'password' => Hash::make('password123'),
                'status' => 1
            ]
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        // Run other seeders
        $this->call([
            RoleTableSeeder::class,
            PermissionTableSeeder::class,
            WeekDaysSeeder::class,
            CurrencySeeder::class,
            TimeZoneSeeder::class,
            LanguageSeeder::class,
            CountrySeeder::class,
        ]);

        // Assign admin role to admin user
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $admin->assignRole($adminRole);
        }
    }
}
