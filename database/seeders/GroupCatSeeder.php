<?php

namespace Database\Seeders;

use App\Models\Group_cat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupCatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groupCategories = [
            ['name' => 'Mahberawi'],
            ['name' => 'Menfesawi'],
            ['name' => 'Timhirtawi'],
            ['name' => 'Limatawi'],
            ['name' => 'Bahilawi'],
            ['name' => 'Habrawi'],
        ];

        foreach ($groupCategories as $category) {
            Group_cat::create($category);
        }
    }
}
