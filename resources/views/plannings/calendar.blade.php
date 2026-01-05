<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Calendar View') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('plannings.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    All Plannings
                </a>
                <a href="{{ route('plannings.dashboard') }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Calendar Controls -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ $startDate->format('F Y') }}
                            </h3>
                            <div class="flex space-x-2">
                                <a href="{{ request()->fullUrlWithQuery(['view' => 'month', 'start_date' => $startDate->copy()->subMonth()->format('Y-m-d')]) }}"
                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-sm">
                                    ← Previous
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['view' => 'month', 'start_date' => now()->format('Y-m-d')]) }}"
                                   class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-1 px-3 rounded text-sm">
                                    Today
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['view' => 'month', 'start_date' => $startDate->copy()->addMonth()->format('Y-m-d')]) }}"
                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-sm">
                                    Next →
                                </a>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <a href="{{ request()->fullUrlWithQuery(['view' => 'month']) }}"
                               class="px-3 py-1 rounded text-sm font-medium {{ $view === 'month' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                                Month
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['view' => 'week']) }}"
                               class="px-3 py-1 rounded text-sm font-medium {{ $view === 'week' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                                Week
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['view' => 'day']) }}"
                               class="px-3 py-1 rounded text-sm font-medium {{ $view === 'day' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                                Day
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($view === 'month')
                        <!-- Month View -->
                        <div class="grid grid-cols-7 gap-1">
                            <!-- Days of week header -->
                            <div class="p-3 text-center text-sm font-medium text-gray-500 bg-gray-50">Sun</div>
                            <div class="p-3 text-center text-sm font-medium text-gray-500 bg-gray-50">Mon</div>
                            <div class="p-3 text-center text-sm font-medium text-gray-500 bg-gray-50">Tue</div>
                            <div class="p-3 text-center text-sm font-medium text-gray-500 bg-gray-50">Wed</div>
                            <div class="p-3 text-center text-sm font-medium text-gray-500 bg-gray-50">Thu</div>
                            <div class="p-3 text-center text-sm font-medium text-gray-500 bg-gray-50">Fri</div>
                            <div class="p-3 text-center text-sm font-medium text-gray-500 bg-gray-50">Sat</div>

                            @php
                                $firstDay = $startDate->copy()->startOfMonth()->startOfWeek();
                                $lastDay = $startDate->copy()->endOfMonth()->endOfWeek();
                                $current = $firstDay->copy();
                            @endphp

                            @while($current->lte($lastDay))
                                @php
                                    $isCurrentMonth = $current->month === $startDate->month;
                                    $isToday = $current->isToday();
                                    $dayPlannings = $plannings->filter(function($planning) use ($current) {
                                        return $current->between($planning->start_date, $planning->end_date);
                                    });
                                @endphp

                                <div class="min-h-24 p-2 border border-gray-200 {{ $isCurrentMonth ? 'bg-white' : 'bg-gray-50' }} {{ $isToday ? 'bg-blue-50' : '' }}">
                                    <div class="flex justify-between items-start mb-1">
                                        <span class="text-sm font-medium {{ $isCurrentMonth ? 'text-gray-900' : 'text-gray-400' }} {{ $isToday ? 'text-blue-600' : '' }}">
                                            {{ $current->day }}
                                        </span>
                                        @if($isToday)
                                            <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                                        @endif
                                    </div>

                                    @if($dayPlannings->count() > 0)
                                        <div class="space-y-1">
                                            @foreach($dayPlannings->take(3) as $planning)
                                                <div class="text-xs p-1 rounded truncate cursor-pointer hover:bg-gray-100
                                                    @if($planning->priority_level === 'critical') bg-red-100 text-red-800
                                                    @elseif($planning->priority_level === 'high') bg-orange-100 text-orange-800
                                                    @elseif($planning->priority_level === 'medium') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800
                                                    @endif"
                                                    onclick="showPlanningDetails({{ $planning->id }})">
                                                    {{ $planning->title }}
                                                </div>
                                            @endforeach
                                            @if($dayPlannings->count() > 3)
                                                <div class="text-xs text-gray-500">
                                                    +{{ $dayPlannings->count() - 3 }} more
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                @php $current->addDay(); @endphp
                            @endwhile
                        </div>

                    @elseif($view === 'week')
                        <!-- Week View -->
                        <div class="grid grid-cols-8 gap-1">
                            <div class="p-3 text-center text-sm font-medium text-gray-500 bg-gray-50">Time</div>
                            @php
                                $weekStart = $startDate->copy()->startOfWeek();
                                $weekDays = collect();
                                for($i = 0; $i < 7; $i++) {
                                    $weekDays->push($weekStart->copy()->addDays($i));
                                }
                            @endphp

                            @foreach($weekDays as $day)
                                <div class="p-3 text-center text-sm font-medium text-gray-500 bg-gray-50 {{ $day->isToday() ? 'bg-blue-100' : '' }}">
                                    <div>{{ $day->format('D') }}</div>
                                    <div class="text-lg font-bold {{ $day->isToday() ? 'text-blue-600' : '' }}">{{ $day->day }}</div>
                                </div>
                            @endforeach

                            <!-- Time slots -->
                            @for($hour = 0; $hour < 24; $hour++)
                                <div class="p-2 text-xs text-gray-500 bg-gray-50 border-b border-gray-200">
                                    {{ sprintf('%02d:00', $hour) }}
                                </div>

                                @foreach($weekDays as $day)
                                    @php
                                        $dayPlannings = $plannings->filter(function($planning) use ($day) {
                                            return $day->between($planning->start_date, $planning->end_date);
                                        });
                                    @endphp

                                    <div class="min-h-12 p-1 border-b border-gray-200 {{ $day->isToday() ? 'bg-blue-50' : 'bg-white' }}">
                                        @foreach($dayPlannings as $planning)
                                            <div class="text-xs p-1 rounded mb-1 cursor-pointer hover:bg-gray-100
                                                @if($planning->priority_level === 'critical') bg-red-100 text-red-800
                                                @elseif($planning->priority_level === 'high') bg-orange-100 text-orange-800
                                                @elseif($planning->priority_level === 'medium') bg-yellow-100 text-yellow-800
                                                @else bg-green-100 text-green-800
                                                @endif"
                                                onclick="showPlanningDetails({{ $planning->id }})">
                                                {{ $planning->title }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            @endfor
                        </div>

                    @else
                        <!-- Day View -->
                        <div class="grid grid-cols-2 gap-1">
                            <div class="p-3 text-center text-sm font-medium text-gray-500 bg-gray-50">Time</div>
                            <div class="p-3 text-center text-sm font-medium text-gray-500 bg-gray-50">
                                {{ $startDate->format('l, F j, Y') }}
                            </div>

                            @for($hour = 0; $hour < 24; $hour++)
                                <div class="p-2 text-xs text-gray-500 bg-gray-50 border-b border-gray-200">
                                    {{ sprintf('%02d:00', $hour) }}
                                </div>

                                @php
                                    $dayPlannings = $plannings->filter(function($planning) use ($startDate) {
                                        return $startDate->between($planning->start_date, $planning->end_date);
                                    });
                                @endphp

                                <div class="min-h-12 p-1 border-b border-gray-200 bg-white">
                                    @foreach($dayPlannings as $planning)
                                        <div class="text-xs p-1 rounded mb-1 cursor-pointer hover:bg-gray-100
                                            @if($planning->priority_level === 'critical') bg-red-100 text-red-800
                                            @elseif($planning->priority_level === 'high') bg-orange-100 text-orange-800
                                            @elseif($planning->priority_level === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800
                                            @endif"
                                            onclick="showPlanningDetails({{ $planning->id }})">
                                            {{ $planning->title }}
                                        </div>
                                    @endforeach
                                </div>
                            @endfor
                        </div>
                    @endif

                    @if($plannings->count() === 0)
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No plannings in this period</h3>
                            <p class="mt-1 text-sm text-gray-500">Try selecting a different time period or create new plannings.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Planning Details Modal -->
    <div id="planning-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modal-title">Planning Details</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="modal-content">
                    <!-- Content will be loaded here -->
                </div>
                <div class="mt-4 flex justify-end">
                    <button onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const plannings = @json($plannings);

        function showPlanningDetails(planningId) {
            const planning = plannings.find(p => p.id === planningId);
            if (!planning) return;

            document.getElementById('modal-title').textContent = planning.title;

            const content = `
                <div class="space-y-3">
                    <div>
                        <span class="font-medium text-gray-700">Description:</span>
                        <p class="text-gray-600">${planning.description || 'No description provided'}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Timeframe:</span>
                        <span class="text-gray-600">${planning.timeframe_type} • ${planning.start_date} - ${planning.end_date}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Priority:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            ${planning.priority_level === 'critical' ? 'bg-red-100 text-red-800' :
                              planning.priority_level === 'high' ? 'bg-orange-100 text-orange-800' :
                              planning.priority_level === 'medium' ? 'bg-yellow-100 text-yellow-800' :
                              'bg-green-100 text-green-800'}">
                            ${planning.priority_level.charAt(0).toUpperCase() + planning.priority_level.slice(1)}
                        </span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            ${planning.status === 'completed' ? 'bg-green-100 text-green-800' :
                              planning.status === 'active' ? 'bg-blue-100 text-blue-800' :
                              planning.status === 'planning' ? 'bg-yellow-100 text-yellow-800' :
                              'bg-gray-100 text-gray-800'}">
                            ${planning.status.charAt(0).toUpperCase() + planning.status.slice(1)}
                        </span>
                    </div>
                    ${planning.budget_amount > 0 ? `
                        <div>
                            <span class="font-medium text-gray-700">Budget:</span>
                            <span class="text-gray-600">$${parseFloat(planning.budget_amount).toFixed(2)}</span>
                        </div>
                    ` : ''}
                    <div class="pt-3 border-t">
                        <a href="/plannings/${planning.id}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                            View Full Details →
                        </a>
                    </div>
                </div>
            `;

            document.getElementById('modal-content').innerHTML = content;
            document.getElementById('planning-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('planning-modal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('planning-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</x-app-layout>
