@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Task Details</h2>
                    <div class="space-x-2">
                        <a href="{{ route('tasks.edit', $task->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Edit Task</a>
                        <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to Tasks</a>
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
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $task->name }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Task details and information.</p>
                        </div>
                        <div>
                            @if($task->for_all && !Auth::user()->isAdmin())
                                @if(isset($userTask))
                                    @if($userTask->status == 'pending')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Your Status: Pending
                                        </span>
                                    @elseif($userTask->status == 'in_progress')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Your Status: In Progress
                                        </span>
                                    @elseif($userTask->status == 'completed')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Your Status: Completed
                                        </span>
                                    @elseif($userTask->status == 'cancelled')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Your Status: Cancelled
                                        </span>
                                    @endif
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Your Status: Not Started
                                    </span>
                                @endif
                            @else
                                @if($task->status == 'pending')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif($task->status == 'in_progress')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        In Progress
                                    </span>
                                @elseif($task->status == 'completed')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                @elseif($task->status == 'cancelled')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Cancelled
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Priority</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
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
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Deadline</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $task->deadline ? $task->deadline->format('F d, Y') : 'No deadline set' }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @if($task->for_all)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            All Users
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">Each user can mark this task as completed for themselves</p>
                                    @elseif($task->group_cat_id)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            Group: {{ $task->groupCat->name }}
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">All members of this group can update this task</p>
                                    @elseif($task->user)
                                        {{ $task->user->name }}
                                    @else
                                        <span class="text-gray-500">Unassigned</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $task->description ?? 'No description provided' }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $task->created_at->format('F d, Y H:i:s') }}
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $task->updated_at->format('F d, Y H:i:s') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <div>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
                                    onclick="return confirm('Are you sure you want to delete this task?')">
                                Delete Task
                            </button>
                        </form>
                    </div>
                    <div>
                        @if($task->for_all && !Auth::user()->isAdmin())
                            @if(!isset($userTask) || $userTask->status != 'completed')
                                <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                                        Mark as Completed for Me
                                    </button>
                                </form>
                            @endif
                        @elseif($task->status != 'completed')
                            <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $task->name }}">
                                <input type="hidden" name="status" value="completed">
                                <input type="hidden" name="priority" value="{{ $task->priority }}">
                                <input type="hidden" name="deadline" value="{{ $task->deadline ? $task->deadline->format('Y-m-d') : '' }}">
                                <input type="hidden" name="description" value="{{ $task->description }}">
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                                    Mark as Completed
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
