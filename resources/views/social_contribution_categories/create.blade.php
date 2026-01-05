@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Create Category</h1>

    <form method="POST" action="{{ route('social-contribution-categories.store') }}" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
            @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="pt-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Create</button>
            <a href="{{ route('social-contribution-categories.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">Cancel</a>
        </div>
    </form>
</div>
@endsection


