@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">My Tasks</h2>
                    <a href="{{ route('tasks.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Add New Task</a>
                </div>

                @if ($message = Session::get('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <!-- Task Filters -->
                <div class="mb-6 bg-gray-50 p-4 rounded-md">
                    <form action="{{ route('tasks.index') }}" method="GET" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="flex-1 min-w-[200px]">
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                            <select id="priority" name="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Priorities</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Filter</button>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($tasks as $task)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $task->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($task->status == 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($task->status == 'in_progress')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                In Progress
                                            </span>
                                        @elseif($task->status == 'completed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        @elseif($task->status == 'cancelled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Cancelled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($task->priority == 'low')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Low
                                            </span>
                                        @elseif($task->priority == 'medium')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Medium
                                            </span>
                                        @elseif($task->priority == 'high')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                High
                                            </span>
                                        @elseif($task->priority == 'urgent')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Urgent
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $task->deadline ? $task->deadline->format('M d, Y') : 'No deadline' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('tasks.show', $task->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">View</a>
                                            <a href="{{ route('tasks.edit', $task->id) }}" class="px-3 py-1 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 transition">Edit</a>
                                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                                                        onclick="return confirm('Are you sure you want to delete this task?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No tasks found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
