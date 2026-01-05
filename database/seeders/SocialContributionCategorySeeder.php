<?php

namespace Database\Seeders;

use App\Models\Social_Contribution_Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialContributionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Education Support'],
            ['name' => 'Health Services'],
            ['name' => 'Community Development'],
            ['name' => 'Youth Programs'],
            ['name' => 'Elder Care'],
            ['name' => 'Emergency Relief'],
            ['name' => 'Cultural Preservation'],
            ['name' => 'Environmental Projects'],
        ];

        foreach ($categories as $category) {
            Social_Contribution_Category::create($category);
        }
    }
}
