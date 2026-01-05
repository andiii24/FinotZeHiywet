<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('FinotZe Hiywet Dashboard') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Community management system</p>
            </div>
            <div class="flex space-x-2">
                <select id="date-filter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="today">Today</option>
                    <option value="week" selected>This Week</option>
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                </select>
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Refresh</button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Banner -->
            <div class="mb-6 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-8 md:px-8 md:flex md:items-center md:justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Welcome back, {{ Auth::user()->name }}!</h2>
                        <p class="mt-2 text-indigo-100">
                            {{ now()->format('l, F j, Y') }} •
                            @if(Auth::user()->isAdmin())
                                <span class="font-semibold">Administrator Account</span>
                            @else
                                <span>Member Account</span>
                            @endif
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('tasks.create') }}" class="inline-block px-6 py-3 bg-white text-indigo-600 font-semibold rounded-md shadow-md hover:bg-indigo-50 transition">
                            Create New Task
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-4 bg-gradient-to-r from-blue-500 to-blue-600">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-white">Members</h3>
                            <span class="rounded-full bg-blue-400 bg-opacity-30 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-white mt-2">{{ $userCount }}</p>
                    </div>
                    <a href="{{ route('group_cats.index') }}" class="block p-3 text-center text-blue-600 hover:text-blue-800 transition border-t border-gray-200">
                        View Groups →
                    </a>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-4 bg-gradient-to-r from-green-500 to-green-600">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-white">Payments</h3>
                            <span class="rounded-full bg-green-400 bg-opacity-30 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-white mt-2">{{ $paymentsCount }}</p>
                    </div>
                    <a href="{{ route('monthly-payments.index') }}" class="block p-3 text-center text-green-600 hover:text-green-800 transition border-t border-gray-200">
                        View Payments →
                    </a>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-4 bg-gradient-to-r from-purple-500 to-purple-600">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-white">Contributions</h3>
                            <span class="rounded-full bg-purple-400 bg-opacity-30 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-white mt-2">{{ $contributionsCount }}</p>
                    </div>
                    <a href="{{ route('social-contributions.index') }}" class="block p-3 text-center text-purple-600 hover:text-purple-800 transition border-t border-gray-200">
                        View Contributions →
                    </a>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-4 bg-gradient-to-r from-amber-500 to-amber-600">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-white">Jobs</h3>
                            <span class="rounded-full bg-amber-400 bg-opacity-30 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-white mt-2">{{ $openJobsCount }}</p>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="block p-3 text-center text-amber-600 hover:text-amber-800 transition border-t border-gray-200">
                        View Jobs →
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <a href="{{ route('group_cats.index') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition text-center">
                    <div class="text-3xl mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="font-bold">Group Categories</div>
                </a>

                <a href="{{ route('monthly-payments.index') }}" class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition text-center">
                    <div class="text-3xl mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="font-bold">Monthly Payments</div>
                </a>

                <a href="{{ route('social-contributions.index') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition text-center">
                    <div class="text-3xl mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <div class="font-bold">Social Contributions</div>
                </a>

                <a href="{{ route('jobs.index') }}" class="bg-gradient-to-br from-amber-500 to-amber-600 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition text-center">
                    <div class="text-3xl mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="font-bold">Jobs</div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Upcoming Events -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-indigo-600">
                        <h3 class="text-lg font-semibold text-white">Upcoming Events</h3>
                    </div>
                    <div class="p-6">
                        <!-- Events from controller -->

                        @if($upcomingEvents->count() > 0)
                            <div class="space-y-4">
                                @foreach($upcomingEvents as $event)
                                    <div class="flex items-start space-x-4 p-3 hover:bg-gray-50 rounded-md transition">
                                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-2 text-center w-14">
                                            <span class="block text-sm font-semibold text-indigo-800">{{ $event->date->format('M') }}</span>
                                            <span class="block text-xl font-bold text-indigo-800">{{ $event->date->format('d') }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $event->name }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $event->date->format('h:i A') }} • {{ $event->location ?? 'No location specified' }}
                                            </p>
                                            <p class="text-xs text-indigo-600 mt-1">{{ $event->category->name }}</p>
                                        </div>
                                        <div>
                                            <a href="{{ route('events.show', $event->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('events.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View all events →</a>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2">No upcoming events</p>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('events.create') }}" class="mt-3 inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                        Create Event
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- My Tasks -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-pink-500 to-pink-600">
                        <h3 class="text-lg font-semibold text-white">My Tasks</h3>
                    </div>
                    <div class="p-6">
                        <!-- Tasks from controller -->

                        @if($myTasks->count() > 0)
                            <div class="space-y-3">
                                @foreach($myTasks as $task)
                                    <div class="flex items-center justify-between p-3 border-l-4
                                        @if($task->priority == 'urgent') border-red-500 bg-red-50
                                        @elseif($task->priority == 'high') border-orange-500 bg-orange-50
                                        @elseif($task->priority == 'medium') border-blue-500 bg-blue-50
                                        @else border-gray-500 bg-gray-50 @endif
                                        rounded-r-md">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 mr-3">
                                                @if($task->status == 'pending')
                                                    <span class="h-4 w-4 bg-yellow-400 rounded-full block"></span>
                                                @elseif($task->status == 'in_progress')
                                                    <span class="h-4 w-4 bg-blue-500 rounded-full block"></span>
                                                @else
                                                    <span class="h-4 w-4 bg-gray-400 rounded-full block"></span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $task->name }}</p>
                                                @if($task->deadline)
                                                    <p class="text-xs text-gray-500">Due: {{ $task->deadline->format('M d, Y') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <a href="{{ route('tasks.show', $task->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('tasks.index') }}" class="text-sm text-pink-600 hover:text-pink-900">View all tasks →</a>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="mt-2">No pending tasks</p>
                                <a href="{{ route('tasks.create') }}" class="mt-3 inline-block px-4 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700 transition">
                                    Create Task
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if(Auth::user()->isAdmin())
                <!-- Admin Quick Links -->
                <div class="mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Admin Actions</h2>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.users.index') }}" class="bg-gradient-to-br from-teal-500 to-teal-600 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition text-center">
                            <div class="text-3xl mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="font-bold">Manage Users</div>
                        </a>

                        <a href="{{ route('admin.group_cats.index') }}" class="bg-gradient-to-br from-violet-500 to-violet-600 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition text-center">
                            <div class="text-3xl mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div class="font-bold">Manage Groups</div>
                        </a>

                        <a href="{{ route('monthly-payments.index') }}?status=pending" class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition text-center">
                            <div class="text-3xl mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="font-bold">Payment Approvals</div>
                        </a>

                        <a href="#" class="bg-gradient-to-br from-red-500 to-red-600 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition text-center">
                            <div class="text-3xl mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="font-bold">Settings</div>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
