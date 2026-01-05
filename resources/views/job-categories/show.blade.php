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
                            <a href="{{ route('job-categories.edit', $category->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Edit Category</a>
                        @endif
                        <a href="{{ route('job-categories.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to Categories</a>
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
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Information about this job category.</p>
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
                                <dt class="text-sm font-medium text-gray-500">Number of Jobs</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->jobs->count() }}</dd>
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

                <!-- Jobs in this category -->
                <div class="mt-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-800">Jobs in this Category</h3>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('jobs.create') }}" class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Add New Job</a>
                        @endif
                    </div>

                    @if($category->jobs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vacancy</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($category->jobs as $job)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if($job->status == 'open')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Open
                                                    </span>
                                                @elseif($job->status == 'closed')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Closed
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Draft
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $job->vacancy }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('jobs.show', $job->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">View</a>
                                                    @if(Auth::user()->isAdmin())
                                                        <a href="{{ route('jobs.edit', $job->id) }}" class="px-3 py-1 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 transition">Edit</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-50 p-4 rounded-md text-center text-gray-500">
                            No jobs found in this category.
                        </div>
                    @endif
                </div>

                @if(Auth::user()->isAdmin())
                    <div class="mt-6">
                        <form action="{{ route('job-categories.destroy', $category->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
                                    onclick="return confirm('Are you sure you want to delete this category? This will also delete all associated jobs.')">
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
