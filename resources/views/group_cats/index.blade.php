@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Group Categories</h2>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.group_cats.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Create New</a>
                        @endif
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($group_cats as $group_cat)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $group_cat->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $group_cat->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('group_cats.show', $group_cat->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">View</a>
                                            @if(auth()->user()->isAdmin())
                                                <a href="{{ route('admin.group_cats.edit', $group_cat->id) }}" class="px-3 py-1 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 transition">Edit</a>
                                                <form action="{{ route('admin.group_cats.destroy', $group_cat->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                                                            onclick="return confirm('Are you sure you want to delete this group category?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
