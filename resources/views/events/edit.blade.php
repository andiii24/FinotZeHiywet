@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Edit Event</h2>
                    <div class="space-x-2">
                        <a href="{{ route('events.show', $event->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">View Event</a>
                        <a href="{{ route('events.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to Events</a>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p class="font-bold">Please fix the following errors:</p>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('events.update', $event->id) }}" method="POST" class="mt-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Event Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $event->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="event_category_id" class="block text-sm font-medium text-gray-700">Category</label>
                            <select id="event_category_id" name="event_category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select a Category</option>
                                @foreach(\App\Models\Events_Category::all() as $category)
                                    <option value="{{ $category->id }}" {{ old('event_category_id', $event->event_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Date & Time</label>
                            <input type="datetime-local" name="date" id="date" value="{{ old('date', $event->date ? $event->date->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $event->description) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Update Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
