@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Events</h2>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('events.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Create New Event</a>
                    @endif
                </div>

                @if ($message = Session::get('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <!-- Event Filters -->
                <div class="mb-6 bg-gray-50 p-4 rounded-md">
                    <form action="{{ route('events.index') }}" method="GET" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select id="category" name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Categories</option>
                                @foreach(\App\Models\Events_Category::all() as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1 min-w-[200px]">
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                            <select id="date" name="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Dates</option>
                                <option value="upcoming" {{ request('date') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="past" {{ request('date') == 'past' ? 'selected' : '' }}>Past</option>
                                <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>This Week</option>
                                <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>This Month</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Filter</button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($events as $event)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                            <div class="p-4">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $event->name }}</h3>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $event->category->name }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500 mb-3">
                                    <div class="flex items-center mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $event->date->format('F d, Y - h:i A') }}
                                    </div>
                                    @if($event->location)
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $event->location }}
                                        </div>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                    {{ Str::limit($event->description, 100) }}
                                </p>
                                <div class="flex justify-end">
                                    <a href="{{ route('events.show', $event->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">View Details</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8 text-gray-500">
                            No events found.
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
