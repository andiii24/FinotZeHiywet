<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $planning->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('plannings.gantt', $planning) }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Gantt Chart
                </a>
                <a href="{{ route('plannings.calendar', $planning) }}"
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Calendar
                </a>
                @if($planning->created_by === auth()->id() || auth()->user()->isAdmin())
                    <a href="{{ route('plannings.edit', $planning) }}"
                       class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                @endif
                <a href="{{ route('plannings.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Planning Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Main Info -->
                        <div class="lg:col-span-2">
                            <div class="flex items-center space-x-3 mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($planning->priority_level === 'critical') bg-red-100 text-red-800
                                    @elseif($planning->priority_level === 'high') bg-orange-100 text-orange-800
                                    @elseif($planning->priority_level === 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($planning->priority_level) }} Priority
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($planning->status === 'completed') bg-green-100 text-green-800
                                    @elseif($planning->status === 'active') bg-blue-100 text-blue-800
                                    @elseif($planning->status === 'planning') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($planning->status) }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    {{ ucfirst($planning->timeframe_type) }}
                                </span>
                            </div>

                            @if($planning->description)
                                <div class="mb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                                    <p class="text-gray-600">{{ $planning->description }}</p>
                                </div>
                            @endif

                            @if($planning->objectives)
                                <div class="mb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Objectives</h3>
                                    <div class="text-gray-600 whitespace-pre-line">{{ $planning->objectives }}</div>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-900">Start Date:</span>
                                    <span class="text-gray-600">{{ $planning->start_date->format('M d, Y') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900">End Date:</span>
                                    <span class="text-gray-600">{{ $planning->end_date->format('M d, Y') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900">Group Category:</span>
                                    <span class="text-gray-600">{{ $planning->groupCat->name }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900">Created by:</span>
                                    <span class="text-gray-600">{{ $planning->creator->name }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="lg:col-span-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium text-gray-700">Progress</span>
                                        <span class="text-sm text-gray-600">{{ $planning->progress_percentage }}%</span>
                                    </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-primary h-2 rounded-full" style="width: {{ $planning->progress_percentage }}%"></div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-blue-50 p-3 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-blue-600">{{ $stats['total_tasks'] }}</div>
                                        <div class="text-sm text-blue-800">Total Tasks</div>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-green-600">{{ $stats['completed_tasks'] }}</div>
                                        <div class="text-sm text-green-800">Completed</div>
                                    </div>
                                    <div class="bg-red-50 p-3 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-red-600">{{ $stats['overdue_tasks'] }}</div>
                                        <div class="text-sm text-red-800">Overdue</div>
                                    </div>
                                    <div class="bg-yellow-50 p-3 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-yellow-600">{{ $planning->planningTasks()->where('status', 'in_progress')->count() }}</div>
                                        <div class="text-sm text-yellow-800">In Progress</div>
                                    </div>
                                </div>

                                @if($planning->budget_amount > 0)
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h4 class="font-medium text-gray-900 mb-2">Budget</h4>
                                        <div class="space-y-2">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">Total Budget:</span>
                                                <span class="font-medium">${{ number_format($planning->budget_amount, 2) }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">Spent:</span>
                                                <span class="font-medium text-red-600">${{ number_format($stats['total_budget_spent'], 2) }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">Remaining:</span>
                                                <span class="font-medium text-green-600">${{ number_format($planning->remaining_budget, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button onclick="showTab('tasks')" id="tasks-tab"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-primary text-primary">
                            Tasks
                        </button>
                        <button onclick="showTab('budget')" id="budget-tab"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Budget
                        </button>
                        <button onclick="showTab('reminders')" id="reminders-tab"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Reminders
                        </button>
                        <button onclick="showTab('team')" id="team-tab"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Team
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    <!-- Tasks Tab -->
                    <div id="tasks-content" class="tab-content">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Tasks</h3>
                            <button onclick="openTaskModal()" class="bg-primary hover:opacity-90 text-white font-bold py-2 px-4 rounded">
                                Add Task
                            </button>
                        </div>

                        @if($planning->planningTasks->count() > 0)
                            <div class="space-y-4">
                                @foreach($planning->planningTasks as $task)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <h4 class="text-lg font-medium text-gray-900">{{ $task->title }}</h4>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($task->priority_level === 'critical') bg-red-100 text-red-800
                                                        @elseif($task->priority_level === 'high') bg-orange-100 text-orange-800
                                                        @elseif($task->priority_level === 'medium') bg-yellow-100 text-yellow-800
                                                        @else bg-green-100 text-green-800
                                                        @endif">
                                                        {{ ucfirst($task->priority_level) }}
                                                    </span>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($task->status === 'completed') bg-green-100 text-green-800
                                                        @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                                        @elseif($task->status === 'not_started') bg-gray-100 text-gray-800
                                                        @elseif($task->status === 'on_hold') bg-yellow-100 text-yellow-800
                                                        @else bg-red-100 text-red-800
                                                        @endif">
                                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                    </span>
                                                </div>

                                                @if($task->description)
                                                    <p class="text-gray-600 mb-2">{{ $task->description }}</p>
                                                @endif

                                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                    <span>{{ $task->start_date->format('M d, Y') }} - {{ $task->end_date->format('M d, Y') }}</span>
                                                    @if($task->assignedUser)
                                                        <span>Assigned to: {{ $task->assignedUser->name }}</span>
                                                    @endif
                                                    @if($task->estimated_hours)
                                                        <span>{{ $task->estimated_hours }}h estimated</span>
                                                    @endif
                                                </div>

                                                @if($task->progress_percentage > 0)
                                                    <div class="mt-2">
                                                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                                                            <span>Progress</span>
                                                            <span>{{ $task->progress_percentage }}%</span>
                                                        </div>
                                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                                            <div class="bg-primary h-2 rounded-full" style="width: {{ $task->progress_percentage }}%"></div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by adding tasks to this planning.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Budget Tab -->
                    <div id="budget-content" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Budget Records</h3>
                            <button onclick="openBudgetModal()" class="bg-primary hover:opacity-90 text-white font-bold py-2 px-4 rounded">
                                Add Record
                            </button>
                        </div>

                        @if($planning->budgetRecords->count() > 0)
                            <div class="space-y-4">
                                @foreach($planning->budgetRecords as $record)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <h4 class="text-lg font-medium text-gray-900">{{ $record->category }}</h4>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $record->budget_type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ ucfirst($record->budget_type) }}
                                                    </span>
                                                </div>

                                                @if($record->description)
                                                    <p class="text-gray-600 mb-2">{{ $record->description }}</p>
                                                @endif

                                                <div class="flex items-center justify-between">
                                                    <div class="text-sm text-gray-500">
                                                        <span>{{ $record->date->format('M d, Y') }}</span>
                                                        @if($record->creator)
                                                            <span class="ml-4">Added by: {{ $record->creator->name }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-lg font-medium {{ $record->budget_type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                                        {{ $record->budget_type === 'income' ? '+' : '-' }}${{ number_format($record->amount, 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No budget records yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Start tracking income and expenses for this planning.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Reminders Tab -->
                    <div id="reminders-content" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Reminders</h3>
                            <button onclick="openReminderModal()" class="bg-primary hover:opacity-90 text-white font-bold py-2 px-4 rounded">
                                Add Reminder
                            </button>
                        </div>

                        @if($planning->reminders->count() > 0)
                            <div class="space-y-4">
                                @foreach($planning->reminders as $reminder)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <h4 class="text-lg font-medium text-gray-900">{{ $reminder->title }}</h4>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $reminder->is_sent ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ $reminder->is_sent ? 'Sent' : 'Pending' }}
                                                    </span>
                                                </div>

                                                @if($reminder->description)
                                                    <p class="text-gray-600 mb-2">{{ $reminder->description }}</p>
                                                @endif

                                                <div class="text-sm text-gray-500">
                                                    <span>{{ $reminder->reminder_time->format('M d, Y H:i') }}</span>
                                                    <span class="ml-4">{{ ucfirst($reminder->reminder_type) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12 7l-2.586-2.586a2 2 0 00-2.828 0L4.828 7z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No reminders yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Set up reminders to stay on track with this planning.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Team Tab -->
                    <div id="team-content" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Team Members</h3>
                            <button onclick="openTeamModal()" class="bg-primary hover:opacity-90 text-white font-bold py-2 px-4 rounded">
                                Add Member
                            </button>
                        </div>

                        @if($planning->assignedUsers->count() > 0)
                            <div class="space-y-4">
                                @foreach($planning->assignedUsers as $user)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                    <span class="text-indigo-600 font-medium">{{ substr($user->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <h4 class="text-lg font-medium text-gray-900">{{ $user->name }}</h4>
                                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($user->pivot->role === 'manager') bg-purple-100 text-purple-800
                                                @elseif($user->pivot->role === 'contributor') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($user->pivot->role) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No team members yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Add team members to collaborate on this planning.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic Budget Summary -->
    <div id="budget-summary" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Budget Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-gray-700">Total Budget</div>
                    <div class="text-2xl font-bold text-gray-900" id="total-budget">${{ number_format($planning->budget_amount, 2) }}</div>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-red-700">Spent</div>
                    <div class="text-2xl font-bold text-red-600" id="total-spent">${{ number_format($stats['total_expense'], 2) }}</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-green-700">Remaining</div>
                    <div class="text-2xl font-bold text-green-600" id="remaining-budget">${{ number_format($planning->budget_amount + $stats['total_income'] - $stats['total_expense'], 2) }}</div>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-blue-700">Spent %</div>
                    <div class="text-2xl font-bold text-blue-600" id="spent-percentage">{{ $planning->budget_amount > 0 ? round(($stats['total_expense'] / $planning->budget_amount) * 100, 1) : 0 }}%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Modal -->
    <div id="task-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add Task</h3>
                    <button onclick="closeTaskModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="task-form">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title *</label>
                            <input type="text" name="title" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Start Date *</label>
                                <input type="date" name="start_date" required min="{{ date('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">End Date *</label>
                                <input type="date" name="end_date" required min="{{ date('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Priority *</label>
                                <select name="priority_level" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Assigned To</label>
                                <select name="assigned_to" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                    <option value="">Select User</option>
                                    @foreach(\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estimated Hours</label>
                            <input type="number" name="estimated_hours" step="0.5" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeTaskModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button type="submit" class="bg-primary hover:opacity-90 text-white font-bold py-2 px-4 rounded">
                            Create Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Budget Modal -->
    <div id="budget-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add Budget Record</h3>
                    <button onclick="closeBudgetModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="budget-form">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category *</label>
                                <input type="text" name="category" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Amount *</label>
                                <input type="number" name="amount" step="0.01" min="0.01" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type *</label>
                                <select name="budget_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                    <option value="expense">Expense</option>
                                    <option value="income">Income</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date *</label>
                                <input type="date" name="date" required min="{{ date('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea name="notes" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeBudgetModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button type="submit" class="bg-primary hover:opacity-90 text-white font-bold py-2 px-4 rounded">
                            Add Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Team Modal -->
    <div id="team-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add Team Member</h3>
                    <button onclick="closeTeamModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="team-form">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">User *</label>
                                <select name="user_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                <option value="">Select User</option>
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role *</label>
                                <select name="role" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                <option value="viewer">Viewer</option>
                                <option value="contributor">Contributor</option>
                                <option value="manager">Manager</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeTeamModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button type="submit" class="bg-primary hover:opacity-90 text-white font-bold py-2 px-4 rounded">
                            Add Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reminder Modal -->
    <div id="reminder-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add Reminder</h3>
                    <button onclick="closeReminderModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="reminder-form">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title *</label>
                            <input type="text" name="title" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date *</label>
                                <input type="date" name="reminder_date" required min="{{ date('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Time *</label>
                                <input type="time" name="reminder_time" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type *</label>
                            <select name="reminder_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                <option value="email">Email</option>
                                <option value="sms">SMS</option>
                                <option value="push">Push Notification</option>
                                <option value="in_app">In-App Notification</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeReminderModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button type="submit" class="bg-primary hover:opacity-90 text-white font-bold py-2 px-4 rounded">
                            Add Reminder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const planningId = {{ $planning->id }};

        function showTab(tabName) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.add('hidden'));

            // Remove active class from all tabs
            const tabs = document.querySelectorAll('[id$="-tab"]');
            tabs.forEach(tab => {
                tab.classList.remove('border-primary', 'text-primary');
                tab.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            document.getElementById(tabName + '-content').classList.remove('hidden');

            // Add active class to selected tab
            const activeTab = document.getElementById(tabName + '-tab');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-primary', 'text-primary');

            // Load data for the active tab
            if (tabName === 'budget') {
                loadBudgetData();
            }
        }

        // Task Modal Functions
        function openTaskModal() {
            document.getElementById('task-modal').classList.remove('hidden');
        }

        function closeTaskModal() {
            document.getElementById('task-modal').classList.add('hidden');
            document.getElementById('task-form').reset();
        }

        // Budget Modal Functions
        function openBudgetModal() {
            document.getElementById('budget-modal').classList.remove('hidden');
        }

        function closeBudgetModal() {
            document.getElementById('budget-modal').classList.add('hidden');
            document.getElementById('budget-form').reset();
        }

        // Reminder Modal Functions
        function openReminderModal() {
            document.getElementById('reminder-modal').classList.remove('hidden');
        }

        function closeReminderModal() {
            document.getElementById('reminder-modal').classList.add('hidden');
            document.getElementById('reminder-form').reset();
        }

        // Team Modal Functions
        function openTeamModal() {
            document.getElementById('team-modal').classList.remove('hidden');
        }

        function closeTeamModal() {
            document.getElementById('team-modal').classList.add('hidden');
            document.getElementById('team-form').reset();
        }

        // Load Budget Data
        function loadBudgetData() {
            fetch(`/plannings/${planningId}/budgets`)
                .then(response => response.json())
                .then(data => {
                    updateBudgetSummary(data.budget_summary);
                    updateBudgetRecords(data.budget_records);
                })
                .catch(error => console.error('Error loading budget data:', error));
        }

        function updateBudgetSummary(summary) {
            document.getElementById('total-budget').textContent = '$' + parseFloat(summary.total_budget).toFixed(2);
            document.getElementById('total-spent').textContent = '$' + parseFloat(summary.total_expense).toFixed(2);
            document.getElementById('remaining-budget').textContent = '$' + parseFloat(summary.remaining_budget).toFixed(2);
            document.getElementById('spent-percentage').textContent = parseFloat(summary.spent_percentage).toFixed(1) + '%';
        }

        function updateBudgetRecords(records) {
            const container = document.querySelector('#budget-content .space-y-4');
            if (records.length === 0) {
                container.innerHTML = '<div class="text-center py-8"><p class="text-gray-500">No budget records yet</p></div>';
                return;
            }

            container.innerHTML = records.map(record => `
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h4 class="text-lg font-medium text-gray-900">${record.category}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${record.budget_type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${record.budget_type.charAt(0).toUpperCase() + record.budget_type.slice(1)}
                                </span>
                            </div>
                            ${record.description ? `<p class="text-gray-600 mb-2">${record.description}</p>` : ''}
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    <span>${new Date(record.date).toLocaleDateString()}</span>
                                </div>
                                <div class="text-lg font-medium ${record.budget_type === 'income' ? 'text-green-600' : 'text-red-600'}">
                                    ${record.budget_type === 'income' ? '+' : '-'}$${parseFloat(record.amount).toFixed(2)}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Form Submissions
        document.getElementById('task-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Client-side validation
            const startDate = new Date(document.querySelector('input[name="start_date"]').value);
            const endDate = new Date(document.querySelector('input[name="end_date"]').value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (startDate < today) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date!',
                    text: 'Start date must be today or later.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (endDate < startDate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date!',
                    text: 'End date must be after or equal to start date.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            const formData = new FormData(this);

            // Add debugging
            console.log('Submitting task form...');
            console.log('Planning ID:', planningId);
            console.log('Form data:', Object.fromEntries(formData));

            // Log individual form fields
            console.log('Title:', formData.get('title'));
            console.log('Start Date:', formData.get('start_date'));
            console.log('End Date:', formData.get('end_date'));
            console.log('Priority:', formData.get('priority_level'));
            console.log('Assigned To:', formData.get('assigned_to'));
            console.log('Estimated Hours:', formData.get('estimated_hours'));

            fetch(`/plannings/${planningId}/tasks`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                return response.text().then(text => {
                    console.log('Response text:', text);
                    try {
                        const data = JSON.parse(text);
                        if (!response.ok) {
                            // Handle validation errors
                            if (response.status === 422 && data.errors) {
                                const errorMessages = Object.values(data.errors).flat().join('\n');
                                throw new Error(`Validation errors:\n${errorMessages}`);
                            }
                            throw new Error(data.message || `HTTP error! status: ${response.status}`);
                        }
                        return data;
                    } catch (e) {
                        if (e.message.includes('Validation errors:')) {
                            throw e;
                        }
                        console.error('Failed to parse JSON:', text);
                        throw new Error('Invalid JSON response');
                    }
                });
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    closeTaskModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Task created successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); // Reload to show new task
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Unknown error occurred.',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message,
                    confirmButtonText: 'OK'
                });
            });
        });

        document.getElementById('budget-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Client-side validation
            const budgetDate = new Date(document.querySelector('input[name="date"]').value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (budgetDate < today) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date!',
                    text: 'Budget date must be today or later.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            const formData = new FormData(this);

            fetch(`/plannings/${planningId}/budgets`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON:', text);
                        throw new Error('Invalid JSON response');
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    closeBudgetModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Budget record created successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        loadBudgetData(); // Refresh budget data
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Unknown error occurred.',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message,
                    confirmButtonText: 'OK'
                });
            });
        });

        document.getElementById('reminder-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Client-side validation
            const reminderDate = new Date(document.querySelector('input[name="reminder_date"]').value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (reminderDate < today) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date!',
                    text: 'Reminder date must be today or later.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            const formData = new FormData(this);

            fetch(`/plannings/${planningId}/reminders`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeReminderModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Reminder created successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); // Reload to show new reminder
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Unknown error occurred.',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message,
                    confirmButtonText: 'OK'
                });
            });
        });

        document.getElementById('team-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch(`/plannings/${planningId}/team`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeTeamModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Team member added successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); // Reload to show new team member
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Unknown error occurred.',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message,
                    confirmButtonText: 'OK'
                });
            });
        });

        // Date validation and auto-update end date
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.querySelector('input[name="start_date"]');
            const endDateInput = document.querySelector('input[name="end_date"]');

            if (startDateInput && endDateInput) {
                startDateInput.addEventListener('change', function() {
                    const startDate = new Date(this.value);
                    const endDate = new Date(endDateInput.value);

                    // If end date is before start date, update it
                    if (endDate < startDate) {
                        endDateInput.value = this.value;
                    }

                    // Update end date min to be start date
                    endDateInput.min = this.value;
                });
            }
        });

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
                closeTaskModal();
                closeBudgetModal();
                closeReminderModal();
                closeTeamModal();
            }
        });
    </script>
</x-app-layout>
