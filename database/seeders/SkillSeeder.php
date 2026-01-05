<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create skills
        $skills = [
            [
                'name' => 'Programming',
                'description' => 'Ability to write computer programs in various languages',
            ],
            [
                'name' => 'Teaching',
                'description' => 'Ability to effectively communicate knowledge to others',
            ],
            [
                'name' => 'Leadership',
                'description' => 'Ability to guide and inspire others',
            ],
            [
                'name' => 'Communication',
                'description' => 'Effective verbal and written communication',
            ],
            [
                'name' => 'Problem Solving',
                'description' => 'Ability to find solutions to complex problems',
            ],
            [
                'name' => 'Project Management',
                'description' => 'Planning, organizing, and managing resources to achieve specific goals',
            ],
        ];

        foreach ($skills as $skillData) {
            Skill::create($skillData);
        }

        // Assign skills to users
        $users = User::all();
        $allSkills = Skill::all();

        foreach ($users as $user) {
            // Randomly assign 2-4 skills to each user with random proficiency levels
            $skillCount = rand(2, 4);
            $randomSkills = $allSkills->random($skillCount);

            foreach ($randomSkills as $skill) {
                $user->skills()->attach($skill->id, [
                    'proficiency_level' => rand(1, 5)
                ]);
            }
        }
    }
}
