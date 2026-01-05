<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Trashed Plannings') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('plannings.index') }}"
                   class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Back to Plannings
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($plannings->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Planning
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Category
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Priority
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Deleted At
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($plannings as $planning)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $planning->title }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $planning->description ? Str::limit($planning->description, 50) : 'No description' }}
                                                        </div>
                                                        <div class="text-xs text-gray-400">
                                                            Created by {{ $planning->creator->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm text-gray-900">
                                                    {{ $planning->groupCat->name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($planning->priority_level === 'critical') bg-red-100 text-red-800
                                                    @elseif($planning->priority_level === 'high') bg-orange-100 text-orange-800
                                                    @elseif($planning->priority_level === 'medium') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800
                                                    @endif">
                                                    {{ ucfirst($planning->priority_level) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($planning->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($planning->status === 'active') bg-blue-100 text-blue-800
                                                    @elseif($planning->status === 'planning') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($planning->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $planning->deleted_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <!-- Restore Button -->
                                                    <form action="{{ route('plannings.restore', $planning) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                onclick="return confirm('Are you sure you want to restore this planning?')"
                                                                class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 px-3 py-1 rounded text-sm">
                                                            Restore
                                                        </button>
                                                    </form>

                                                    <!-- Force Delete Button -->
                                                    <form action="{{ route('plannings.force-delete', $planning) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                onclick="return confirm('Are you sure you want to permanently delete this planning? This action cannot be undone!')"
                                                                class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded text-sm">
                                                            Delete Forever
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $plannings->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No trashed plannings</h3>
                        <p class="mt-1 text-sm text-gray-500">There are no deleted plannings to restore.</p>
                        <div class="mt-6">
                            <a href="{{ route('plannings.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                View All Plannings
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
