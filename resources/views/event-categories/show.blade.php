@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">{{ $category->name }}</h2>
                    <div class="space-x-2">
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('event-categories.edit', $category->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Edit Category</a>
                        @endif
                        <a href="{{ route('event-categories.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to Categories</a>
                    </div>
                </div>

                @if ($message = Session::get('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Category Details</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Information about this event category.</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Category ID</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->id }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Category Name</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->name }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Number of Events</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->events->count() }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->created_at->format('F d, Y H:i:s') }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->updated_at->format('F d, Y H:i:s') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Events in this category -->
                <div class="mt-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-800">Events in this Category</h3>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('events.create') }}" class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Create New Event</a>
                        @endif
                    </div>

                    @if($category->events->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($category->events as $event)
                                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                                    <div class="p-4">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $event->name }}</h3>
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
                                        <div class="flex justify-end">
                                            <a href="{{ route('events.show', $event->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 p-4 rounded-md text-center text-gray-500">
                            No events found in this category.
                        </div>
                    @endif
                </div>

                @if(Auth::user()->isAdmin())
                    <div class="mt-6">
                        <form action="{{ route('event-categories.destroy', $category->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
                                    onclick="return confirm('Are you sure you want to delete this category? This will also delete all associated events.')">
                                Delete Category
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
