<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Planning;
use App\Models\PlanningTask;
use App\Models\PlanningReminder;
use App\Models\PlanningBudget;
use App\Models\Group_cat;
use App\Models\User;
use Carbon\Carbon;

class PlanningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users and group categories
        $users = User::all();
        $groupCats = Group_cat::all();

        if ($users->isEmpty() || $groupCats->isEmpty()) {
            $this->command->warn('No users or group categories found. Please run other seeders first.');
            return;
        }

        // Create sample plannings
        $plannings = [
            [
                'title' => 'Q1 2024 Strategic Planning',
                'description' => 'Comprehensive strategic planning for the first quarter of 2024, focusing on organizational growth and development.',
                'objectives' => "1. Increase member engagement by 25%\n2. Launch new community programs\n3. Improve financial sustainability\n4. Enhance digital presence",
                'timeframe_type' => 'quarterly',
                'start_date' => Carbon::now()->startOfQuarter(),
                'end_date' => Carbon::now()->endOfQuarter(),
                'priority_level' => 'high',
                'group_cat_id' => $groupCats->first()->id,
                'group_list' => $groupCats->take(2)->pluck('id')->toArray(),
                'budget_amount' => 50000.00,
                'status' => 'active',
                'created_by' => $users->first()->id,
                'progress_percentage' => 35.5,
                'is_public' => true,
            ],
            [
                'title' => 'Annual Community Development Initiative',
                'description' => 'Year-long initiative to strengthen community bonds and improve member satisfaction.',
                'objectives' => "1. Organize 12 community events\n2. Establish mentorship programs\n3. Create member feedback system\n4. Develop leadership training",
                'timeframe_type' => 'yearly',
                'start_date' => Carbon::now()->startOfYear(),
                'end_date' => Carbon::now()->endOfYear(),
                'priority_level' => 'critical',
                'group_cat_id' => $groupCats->skip(1)->first()->id ?? $groupCats->first()->id,
                'group_list' => $groupCats->pluck('id')->toArray(),
                'budget_amount' => 150000.00,
                'status' => 'active',
                'created_by' => $users->skip(1)->first()->id ?? $users->first()->id,
                'progress_percentage' => 60.0,
                'is_public' => true,
            ],
            [
                'title' => 'Monthly Member Outreach Program',
                'description' => 'Regular monthly activities to engage with members and gather feedback.',
                'objectives' => "1. Conduct monthly surveys\n2. Organize member meetings\n3. Send newsletters\n4. Track engagement metrics",
                'timeframe_type' => 'monthly',
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->endOfMonth(),
                'priority_level' => 'medium',
                'group_cat_id' => $groupCats->first()->id,
                'group_list' => [],
                'budget_amount' => 5000.00,
                'status' => 'planning',
                'created_by' => $users->first()->id,
                'progress_percentage' => 0,
                'is_public' => true,
            ],
        ];

        foreach ($plannings as $planningData) {
            $planning = Planning::create($planningData);

            // Create sample tasks for each planning
            $tasks = [
                [
                    'title' => 'Initial Planning Meeting',
                    'description' => 'Conduct initial planning meeting with stakeholders to define scope and objectives.',
                    'start_date' => $planning->start_date,
                    'end_date' => $planning->start_date->addDays(3),
                    'priority_level' => 'high',
                    'status' => 'completed',
                    'progress_percentage' => 100,
                    'estimated_hours' => 8,
                    'actual_hours' => 8,
                    'assigned_to' => $users->first()->id,
                    'created_by' => $planning->created_by,
                ],
                [
                    'title' => 'Resource Allocation',
                    'description' => 'Allocate necessary resources and budget for the planning implementation.',
                    'start_date' => $planning->start_date->addDays(1),
                    'end_date' => $planning->start_date->addDays(7),
                    'priority_level' => 'high',
                    'status' => 'in_progress',
                    'progress_percentage' => 75,
                    'estimated_hours' => 16,
                    'actual_hours' => 12,
                    'assigned_to' => $users->skip(1)->first()->id ?? $users->first()->id,
                    'created_by' => $planning->created_by,
                ],
                [
                    'title' => 'Implementation Phase',
                    'description' => 'Execute the main implementation activities according to the plan.',
                    'start_date' => $planning->start_date->addDays(10),
                    'end_date' => $planning->end_date->subDays(10),
                    'priority_level' => 'critical',
                    'status' => 'not_started',
                    'progress_percentage' => 0,
                    'estimated_hours' => 40,
                    'assigned_to' => $users->first()->id,
                    'created_by' => $planning->created_by,
                ],
                [
                    'title' => 'Review and Evaluation',
                    'description' => 'Review progress and evaluate outcomes against objectives.',
                    'start_date' => $planning->end_date->subDays(7),
                    'end_date' => $planning->end_date,
                    'priority_level' => 'medium',
                    'status' => 'not_started',
                    'progress_percentage' => 0,
                    'estimated_hours' => 12,
                    'assigned_to' => $users->skip(1)->first()->id ?? $users->first()->id,
                    'created_by' => $planning->created_by,
                ],
            ];

            foreach ($tasks as $taskData) {
                $taskData['planning_id'] = $planning->id;
                PlanningTask::create($taskData);
            }

            // Create sample budget records
            $budgetRecords = [
                [
                    'category' => 'Personnel',
                    'description' => 'Staff costs for planning implementation',
                    'amount' => $planning->budget_amount * 0.4,
                    'budget_type' => 'expense',
                    'date' => $planning->start_date,
                    'created_by' => $planning->created_by,
                ],
                [
                    'category' => 'Materials',
                    'description' => 'Materials and supplies needed',
                    'amount' => $planning->budget_amount * 0.2,
                    'budget_type' => 'expense',
                    'date' => $planning->start_date->addDays(5),
                    'created_by' => $planning->created_by,
                ],
                [
                    'category' => 'External Funding',
                    'description' => 'Grant received for this planning',
                    'amount' => $planning->budget_amount * 0.3,
                    'budget_type' => 'income',
                    'date' => $planning->start_date->addDays(2),
                    'created_by' => $planning->created_by,
                ],
            ];

            foreach ($budgetRecords as $budgetData) {
                $budgetData['planning_id'] = $planning->id;
                PlanningBudget::create($budgetData);
            }

            // Create sample reminders
            $reminders = [
                [
                    'title' => 'Planning Review Meeting',
                    'description' => 'Weekly review meeting to assess progress and address any issues.',
                    'reminder_date' => Carbon::now()->addDays(7),
                    'reminder_time' => Carbon::now()->addDays(7)->setTime(10, 0),
                    'reminder_type' => 'email',
                    'created_by' => $planning->created_by,
                    'recipients' => [$planning->created_by],
                ],
                [
                    'title' => 'Budget Review',
                    'description' => 'Monthly budget review to ensure financial targets are being met.',
                    'reminder_date' => Carbon::now()->addDays(14),
                    'reminder_time' => Carbon::now()->addDays(14)->setTime(14, 0),
                    'reminder_type' => 'in_app',
                    'created_by' => $planning->created_by,
                    'recipients' => [$planning->created_by],
                ],
            ];

            foreach ($reminders as $reminderData) {
                $reminderData['planning_id'] = $planning->id;
                PlanningReminder::create($reminderData);
            }
        }

        $this->command->info('Planning data seeded successfully!');
    }
}
