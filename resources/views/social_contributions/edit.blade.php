@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Social Contribution</h1>

    <form method="POST" action="{{ route('social-contributions.update', $contribution->id) }}" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" value="{{ old('title', $contribution->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
            @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Category</label>
            <select name="social_contribution_category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('social_contribution_category_id', $contribution->social_contribution_category_id)==$category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('social_contribution_category_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date" value="{{ old('date', optional($contribution->date)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                @error('date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="open" @selected(old('type', $contribution->type)==='open')>Open</option>
                    <option value="fixed" @selected(old('type', $contribution->type)==='fixed')>Fixed</option>
                </select>
                @error('type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Target Amount</label>
                <input type="number" step="0.01" name="target_amount" value="{{ old('target_amount', $contribution->target_amount) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
                @error('target_amount')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Single Amount (optional)</label>
                <input type="number" step="0.01" name="single_amount" value="{{ old('single_amount', $contribution->single_amount) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                @error('single_amount')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Location</label>
            <input type="text" name="location" value="{{ old('location', $contribution->location) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('location')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $contribution->description) }}</textarea>
            @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="pt-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Save</button>
            <a href="{{ route('social-contributions.show', $contribution->id) }}" class="ml-3 text-gray-600 hover:text-gray-800">Cancel</a>
        </div>
    </form>
</div>
@endsection


