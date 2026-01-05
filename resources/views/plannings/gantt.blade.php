<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gantt Chart') }}: {{ $planning->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('plannings.show', $planning) }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Planning
                </a>
                <a href="{{ route('plannings.calendar', $planning) }}"
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Calendar View
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Planning Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
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
                        <span class="text-sm text-gray-500">
                            {{ $planning->start_date->format('M d, Y') }} - {{ $planning->end_date->format('M d, Y') }}
                        </span>
                    </div>

                    @if($planning->progress_percentage > 0)
                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Overall Progress</span>
                                <span>{{ $planning->progress_percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $planning->progress_percentage }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Gantt Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Task Timeline</h3>
                        <div class="flex space-x-2">
                            <button onclick="zoomIn()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-sm">
                                Zoom In
                            </button>
                            <button onclick="zoomOut()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-sm">
                                Zoom Out
                            </button>
                            <button onclick="resetZoom()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-sm">
                                Reset
                            </button>
                        </div>
                    </div>

                    @if($planning->planningTasks->count() > 0)
                        <div class="overflow-x-auto">
                            <div id="gantt-container" class="min-w-full">
                                <!-- Timeline Header -->
                                <div class="flex border-b border-gray-200 mb-4">
                                    <div class="w-80 flex-shrink-0 p-3 font-medium text-gray-900">Task</div>
                                    <div class="flex-1 p-3 font-medium text-gray-900">Timeline</div>
                                    <div class="w-24 flex-shrink-0 p-3 font-medium text-gray-900 text-center">Progress</div>
                                    <div class="w-32 flex-shrink-0 p-3 font-medium text-gray-900 text-center">Status</div>
                                </div>

                                <!-- Tasks -->
                                @foreach($planning->planningTasks as $task)
                                    <div class="flex border-b border-gray-100 py-4 hover:bg-gray-50">
                                        <!-- Task Info -->
                                        <div class="w-80 flex-shrink-0 p-3">
                                            <div class="flex items-center space-x-2">
                                                <h4 class="text-sm font-medium text-gray-900">{{ $task->title }}</h4>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                    @if($task->priority_level === 'critical') bg-red-100 text-red-800
                                                    @elseif($task->priority_level === 'high') bg-orange-100 text-orange-800
                                                    @elseif($task->priority_level === 'medium') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800
                                                    @endif">
                                                    {{ ucfirst($task->priority_level) }}
                                                </span>
                                            </div>
                                            @if($task->assignedUser)
                                                <p class="text-xs text-gray-500 mt-1">Assigned to: {{ $task->assignedUser->name }}</p>
                                            @endif
                                            @if($task->description)
                                                <p class="text-xs text-gray-600 mt-1">{{ Str::limit($task->description, 60) }}</p>
                                            @endif
                                        </div>

                                        <!-- Timeline Bar -->
                                        <div class="flex-1 p-3">
                                            <div class="relative h-8 bg-gray-100 rounded-lg overflow-hidden">
                                                @php
                                                    $startDate = $task->start_date;
                                                    $endDate = $task->end_date;
                                                    $planningStart = $planning->start_date;
                                                    $planningEnd = $planning->end_date;

                                                    // Calculate position and width as percentages
                                                    $totalDays = $planningStart->diffInDays($planningEnd);
                                                    $taskStartOffset = $planningStart->diffInDays($startDate);
                                                    $taskDuration = $startDate->diffInDays($endDate);

                                                    $leftPosition = $totalDays > 0 ? ($taskStartOffset / $totalDays) * 100 : 0;
                                                    $width = $totalDays > 0 ? ($taskDuration / $totalDays) * 100 : 10;

                                                    // Ensure minimum width and proper positioning
                                                    $width = max($width, 2);
                                                    $leftPosition = min($leftPosition, 100 - $width);
                                                @endphp

                                                <div class="absolute h-full rounded-lg
                                                    @if($task->status === 'completed') bg-green-500
                                                    @elseif($task->status === 'in_progress') bg-blue-500
                                                    @elseif($task->status === 'on_hold') bg-yellow-500
                                                    @elseif($task->status === 'cancelled') bg-red-500
                                                    @else bg-gray-400
                                                    @endif"
                                                    style="left: {{ $leftPosition }}%; width: {{ $width }}%;">

                                                    <!-- Progress overlay -->
                                                    @if($task->progress_percentage > 0)
                                                        <div class="absolute inset-0 bg-white bg-opacity-30 rounded-lg"
                                                             style="width: {{ 100 - $task->progress_percentage }}%; right: 0;"></div>
                                                    @endif
                                                </div>

                                                <!-- Task dates -->
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-white drop-shadow-sm">
                                                        {{ $startDate->format('M d') }} - {{ $endDate->format('M d') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Progress -->
                                        <div class="w-24 flex-shrink-0 p-3 text-center">
                                            <div class="text-sm font-medium text-gray-900">{{ $task->progress_percentage }}%</div>
                                            @if($task->estimated_hours)
                                                <div class="text-xs text-gray-500">{{ $task->estimated_hours }}h</div>
                                            @endif
                                        </div>

                                        <!-- Status -->
                                        <div class="w-32 flex-shrink-0 p-3 text-center">
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
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="mt-8 flex flex-wrap gap-4 text-sm">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                                <span>Completed</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                                <span>In Progress</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                                <span>On Hold</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-gray-400 rounded mr-2"></div>
                                <span>Not Started</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                                <span>Cancelled</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Add tasks to see the Gantt chart timeline.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        let zoomLevel = 1;

        function zoomIn() {
            zoomLevel = Math.min(zoomLevel * 1.2, 3);
            updateZoom();
        }

        function zoomOut() {
            zoomLevel = Math.max(zoomLevel / 1.2, 0.5);
            updateZoom();
        }

        function resetZoom() {
            zoomLevel = 1;
            updateZoom();
        }

        function updateZoom() {
            const container = document.getElementById('gantt-container');
            container.style.transform = `scale(${zoomLevel})`;
            container.style.transformOrigin = 'top left';
        }
    </script>
</x-app-layout>
