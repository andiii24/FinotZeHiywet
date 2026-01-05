<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            GroupCatSeeder::class, // Create group categories first
            AdminUserSeeder::class, // Then create users
            SkillSeeder::class,    // Assign skills to users
            SocialContributionCategorySeeder::class, // Create social contribution categories
            JobCategorySeeder::class, // Create job categories
            EventsCategorySeeder::class, // Create event categories
            PlanningSeeder::class, // Create planning data
        ]);
    }
}
