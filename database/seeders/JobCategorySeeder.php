<?php

namespace Database\Seeders;

use App\Models\Job_Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Information Technology'],
            ['name' => 'Healthcare'],
            ['name' => 'Education'],
            ['name' => 'Finance'],
            ['name' => 'Marketing'],
            ['name' => 'Sales'],
            ['name' => 'Engineering'],
            ['name' => 'Customer Service'],
            ['name' => 'Administrative'],
            ['name' => 'Human Resources'],
        ];

        foreach ($categories as $category) {
            Job_Category::create($category);
        }
    }
}
