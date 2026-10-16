@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">My Resource Submissions</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('spiritual-resources.suggest') }}" class="px-4 py-2 bg-primary text-white rounded-md hover:opacity-90 transition">Suggest Another</a>
                        <a href="{{ route('spiritual-resources.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to Library</a>
                    </div>
                </div>


                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($resources as $resource)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $resource->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $resource->type_label }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($resource->isPending())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">Pending Review</span>
                                        @elseif($resource->isApproved())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                            @if($resource->reviewer)
                                                <p class="text-xs text-gray-500 mt-1">Approved by {{ $resource->reviewer->name }}</p>
                                            @endif
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                            @if($resource->reviewer)
                                                <p class="text-xs text-gray-500 mt-1">Rejected by {{ $resource->reviewer->name }}</p>
                                            @endif
                                            @if($resource->rejection_reason)
                                                <p class="text-xs text-gray-500 mt-1 max-w-xs">{{ Str::limit($resource->rejection_reason, 80) }}</p>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $resource->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('spiritual-resources.show', $resource) }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        You have not submitted any resources yet.
                                        <a href="{{ route('spiritual-resources.suggest') }}" class="text-primary underline ml-1">Suggest one</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $resources->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
