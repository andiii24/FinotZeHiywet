<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get group categories
        $groupCategories = \App\Models\Group_cat::all();

        if ($groupCategories->isEmpty()) {
            // If no group categories exist, create a default one
            $defaultGroup = \App\Models\Group_cat::create(['name' => 'Default Group']);
            $groupCategories = \App\Models\Group_cat::all();
        }

        // Create admin users
        $admins = [
            [
                'name' => 'Main Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'marital_status' => 'Married',
                'education_background' => 'Masters in Computer Science',
                'work_status' => true,
                'job_title' => 'System Administrator',
                'work_place' => 'FinotZe Organization',
                'group_cat_id' => $groupCategories->get(0)->id,
            ],
            [
                'name' => 'Secondary Admin',
                'email' => 'admin2@example.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'marital_status' => 'Single',
                'education_background' => 'Bachelor in IT',
                'work_status' => true,
                'job_title' => 'IT Manager',
                'work_place' => 'FinotZe Organization',
                'group_cat_id' => $groupCategories->get(1)->id,
            ],
        ];

        foreach ($admins as $admin) {
            User::create($admin);
        }

        // Create regular users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_USER,
                'marital_status' => 'Married',
                'education_background' => 'Bachelor in Economics',
                'work_status' => true,
                'job_title' => 'Accountant',
                'work_place' => 'ABC Company',
                'group_cat_id' => $groupCategories->get(2)->id,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_USER,
                'marital_status' => 'Single',
                'education_background' => 'Masters in Education',
                'work_status' => true,
                'job_title' => 'Teacher',
                'work_place' => 'Local School',
                'group_cat_id' => $groupCategories->get(3)->id,
            ],
            [
                'name' => 'Michael Johnson',
                'email' => 'michael@example.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_USER,
                'marital_status' => 'Married',
                'education_background' => 'PhD in Engineering',
                'work_status' => true,
                'job_title' => 'Engineer',
                'work_place' => 'Tech Solutions Inc.',
                'group_cat_id' => $groupCategories->get(4)->id,
            ],
            [
                'name' => 'Sarah Williams',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_USER,
                'marital_status' => 'Single',
                'education_background' => 'Bachelor in Arts',
                'work_status' => false,
                'job_title' => null,
                'work_place' => null,
                'group_cat_id' => $groupCategories->get(5 % $groupCategories->count())->id,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
