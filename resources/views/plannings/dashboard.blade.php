<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Planning Dashboard') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('plannings.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    All Plannings
                </a>
                <a href="{{ route('plannings.create') }}"
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Create Planning
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Plannings</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['total_plannings'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Active Plannings</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['active_plannings'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Completed</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['completed_plannings'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Overdue</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['overdue_plannings'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Priority Distribution Chart -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Priority Distribution</h3>
                        <div class="space-y-3">
                            @foreach(['critical' => 'Critical', 'high' => 'High', 'medium' => 'Medium', 'low' => 'Low'] as $priority => $label)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                                    <div class="flex items-center">
                                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="h-2 rounded-full
                                                @if($priority === 'critical') bg-red-500
                                                @elseif($priority === 'high') bg-orange-500
                                                @elseif($priority === 'medium') bg-yellow-500
                                                @else bg-green-500
                                                @endif"
                                                style="width: {{ $priorityDistribution->get($priority, 0) > 0 ? ($priorityDistribution->get($priority, 0) / $stats['total_plannings']) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-600 w-8">{{ $priorityDistribution->get($priority, 0) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Status Distribution Chart -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Status Distribution</h3>
                        <div class="space-y-3">
                            @foreach(['planning' => 'Planning', 'active' => 'Active', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $status => $label)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                                    <div class="flex items-center">
                                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="h-2 rounded-full
                                                @if($status === 'completed') bg-green-500
                                                @elseif($status === 'active') bg-blue-500
                                                @elseif($status === 'planning') bg-yellow-500
                                                @else bg-gray-500
                                                @endif"
                                                style="width: {{ $statusDistribution->get($status, 0) > 0 ? ($statusDistribution->get($status, 0) / $stats['total_plannings']) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-600 w-8">{{ $statusDistribution->get($status, 0) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Plannings -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Plannings</h3>
                            <a href="{{ route('plannings.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">View all</a>
                        </div>
                        @if($recentPlannings->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentPlannings as $planning)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-medium text-gray-900">
                                                    <a href="{{ route('plannings.show', $planning) }}" class="hover:text-indigo-600">
                                                        {{ $planning->title }}
                                                    </a>
                                                </h4>
                                                <div class="flex items-center space-x-3 mt-2">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($planning->priority_level === 'critical') bg-red-100 text-red-800
                                                        @elseif($planning->priority_level === 'high') bg-orange-100 text-orange-800
                                                        @elseif($planning->priority_level === 'medium') bg-yellow-100 text-yellow-800
                                                        @else bg-green-100 text-green-800
                                                        @endif">
                                                        {{ ucfirst($planning->priority_level) }}
                                                    </span>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($planning->status === 'completed') bg-green-100 text-green-800
                                                        @elseif($planning->status === 'active') bg-blue-100 text-blue-800
                                                        @elseif($planning->status === 'planning') bg-yellow-100 text-yellow-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ ucfirst($planning->status) }}
                                                    </span>
                                                    <span class="text-sm text-gray-500">{{ $planning->created_at->diffForHumans() }}</span>
                                                </div>
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
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No plannings yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating your first planning.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Upcoming Deadlines -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Upcoming Deadlines</h3>
                            <span class="text-sm text-gray-500">Next 30 days</span>
                        </div>
                        @if($upcomingDeadlines->count() > 0)
                            <div class="space-y-4">
                                @foreach($upcomingDeadlines as $planning)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-medium text-gray-900">
                                                    <a href="{{ route('plannings.show', $planning) }}" class="hover:text-indigo-600">
                                                        {{ $planning->title }}
                                                    </a>
                                                </h4>
                                                <div class="flex items-center space-x-3 mt-2">
                                                    <span class="text-sm text-gray-500">
                                                        Due: {{ $planning->end_date->format('M d, Y') }}
                                                    </span>
                                                    <span class="text-sm text-gray-500">
                                                        ({{ $planning->end_date->diffForHumans() }})
                                                    </span>
                                                </div>
                                                @if($planning->progress_percentage > 0)
                                                    <div class="mt-2">
                                                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                                                            <span>Progress</span>
                                                            <span>{{ $planning->progress_percentage }}%</span>
                                                        </div>
                                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $planning->progress_percentage }}%"></div>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No upcoming deadlines</h3>
                                <p class="mt-1 text-sm text-gray-500">You're all caught up!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
