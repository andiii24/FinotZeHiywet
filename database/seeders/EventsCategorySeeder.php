<?php

namespace Database\Seeders;

use App\Models\Events_Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Conference'],
            ['name' => 'Workshop'],
            ['name' => 'Seminar'],
            ['name' => 'Networking'],
            ['name' => 'Fundraising'],
            ['name' => 'Community Service'],
            ['name' => 'Celebration'],
            ['name' => 'Meeting'],
        ];

        foreach ($categories as $category) {
            Events_Category::create($category);
        }
    }
}
