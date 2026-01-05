<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Planning Management') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('plannings.dashboard') }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Dashboard
                </a>
                <a href="{{ route('plannings.trashed') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Trash
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
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('plannings.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="timeframe_type" class="block text-sm font-medium text-gray-700">Timeframe</label>
                            <select name="timeframe_type" id="timeframe_type"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Timeframes</option>
                                @foreach($timeframeTypes as $key => $value)
                                    <option value="{{ $key }}" {{ request('timeframe_type') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $key => $value)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="priority_level" class="block text-sm font-medium text-gray-700">Priority</label>
                            <select name="priority_level" id="priority_level"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Priorities</option>
                                @foreach($priorityLevels as $key => $value)
                                    <option value="{{ $key }}" {{ request('priority_level') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="group_cat_id" class="block text-sm font-medium text-gray-700">Group Category</label>
                            <select name="group_cat_id" id="group_cat_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Groups</option>
                                @foreach($groupCats as $groupCat)
                                    <option value="{{ $groupCat->id }}" {{ request('group_cat_id') == $groupCat->id ? 'selected' : '' }}>
                                        {{ $groupCat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-5">
                            <button type="submit"
                                    class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Filter
                            </button>
                            <a href="{{ route('plannings.index') }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Plannings List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($plannings->count() > 0)
                        <div class="grid gap-6">
                            @foreach($plannings as $planning)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    <a href="{{ route('plannings.show', $planning) }}"
                                                       class="hover:text-indigo-600">
                                                        {{ $planning->title }}
                                                    </a>
                                                </h3>
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
                                            </div>

                                            @if($planning->description)
                                                <p class="text-gray-600 mb-3">{{ Str::limit($planning->description, 150) }}</p>
                                            @endif

                                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $planning->timeframe_type }} • {{ $planning->start_date->format('M d, Y') }} - {{ $planning->end_date->format('M d, Y') }}
                                                </div>

                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    {{ $planning->groupCat->name }}
                                                </div>

                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    {{ $planning->creator->name }}
                                                </div>

                                                @if($planning->budget_amount > 0)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                        </svg>
                                                        ${{ number_format($planning->budget_amount, 2) }}
                                                    </div>
                                                @endif
                                            </div>

                                            @if($planning->progress_percentage > 0)
                                                <div class="mt-3">
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

                                        <div class="flex space-x-2 ml-4">
                                            <a href="{{ route('plannings.show', $planning) }}"
                                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                View
                                            </a>
                                            @if($planning->created_by === auth()->id() || auth()->user()->isAdmin())
                                                <a href="{{ route('plannings.edit', $planning) }}"
                                                   class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">
                                                    Edit
                                                </a>
                                                <button type="button"
                                                        onclick="deletePlanning({{ $planning->id }}, '{{ $planning->title }}')"
                                                        class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                    Delete
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $plannings->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No plannings found</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new planning.</p>
                            <div class="mt-6">
                                <a href="{{ route('plannings.create') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Create Planning
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function deletePlanning(planningId, planningTitle) {
            Swal.fire({
                title: 'Delete Planning?',
                text: `Are you sure you want to delete "${planningTitle}"? This will move it to trash and can be restored later.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit the form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/plannings/${planningId}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>
