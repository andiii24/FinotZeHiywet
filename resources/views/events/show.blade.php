@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">{{ $event->name }}</h2>
                    <div class="space-x-2">
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('events.edit', $event->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Edit Event</a>
                        @endif
                        <a href="{{ route('events.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to Events</a>
                    </div>
                </div>

                @if ($message = Session::get('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Event Details</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Information about this event.</p>
                        </div>
                        <div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $event->category->name }}
                            </span>
                        </div>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Event Name</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $event->name }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $event->date->format('F d, Y - h:i A') }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Location</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $event->location ?? 'No location specified' }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Category</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <a href="{{ route('event-categories.show', $event->category->id) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $event->category->name }}
                                    </a>
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Created By</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $event->creator ? $event->creator->name : 'Unknown' }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $event->created_at->format('F d, Y H:i:s') }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <div class="prose max-w-none">
                                        {{ $event->description ?? 'No description provided' }}
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                @if(Auth::user()->isAdmin())
                    <div class="mt-6">
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
                                    onclick="return confirm('Are you sure you want to delete this event?')">
                                Delete Event
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
