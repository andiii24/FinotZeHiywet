@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Create New Task</h2>
                    <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to Tasks</a>
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

                <form action="{{ route('tasks.store') }}" method="POST" class="mt-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Task Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                            <select id="priority" name="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>

                        <div>
                            <label for="deadline" class="block text-sm font-medium text-gray-700">Deadline</label>
                            <input type="date" name="deadline" id="deadline" value="{{ old('deadline') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>

                        @if(Auth::user()->isAdmin())
                            <div class="md:col-span-2">
                                <label for="assignment_type" class="block text-sm font-medium text-gray-700">Assignment Type</label>
                                <select id="assignment_type" name="assignment_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <option value="user" {{ old('assignment_type') == 'user' ? 'selected' : '' }}>Individual User</option>
                                    <option value="group" {{ old('assignment_type') == 'group' ? 'selected' : '' }}>Group</option>
                                    <option value="all" {{ old('assignment_type') == 'all' ? 'selected' : '' }}>All Users</option>
                                </select>
                            </div>

                            <div id="user-assignment" class="md:col-span-2 {{ old('assignment_type') == 'group' || old('assignment_type') == 'all' ? 'hidden' : '' }}">
                                <label for="user_id" class="block text-sm font-medium text-gray-700">Assign To User</label>
                                <select id="user_id" name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : (Auth::id() == $user->id ? 'selected' : '') }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="group-assignment" class="md:col-span-2 {{ old('assignment_type') != 'group' ? 'hidden' : '' }}">
                                <label for="group_cat_id" class="block text-sm font-medium text-gray-700">Assign To Group</label>
                                <select id="group_cat_id" name="group_cat_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @foreach($groupCategories as $group)
                                        <option value="{{ $group->id }}" {{ old('group_cat_id') == $group->id ? 'selected' : '' }}>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="all-assignment" class="md:col-span-2 {{ old('assignment_type') != 'all' ? 'hidden' : '' }}">
                                <p class="text-sm text-gray-500">This task will be assigned to all users. Only administrators can mark global tasks as completed.</p>
                            </div>
                        @else
                            <input type="hidden" name="assignment_type" value="user">
                        @endif

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:opacity-90 transition">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->isAdmin())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const assignmentType = document.getElementById('assignment_type');
        const userAssignment = document.getElementById('user-assignment');
        const groupAssignment = document.getElementById('group-assignment');
        const allAssignment = document.getElementById('all-assignment');

        assignmentType.addEventListener('change', function() {
            // Hide all assignment sections
            userAssignment.classList.add('hidden');
            groupAssignment.classList.add('hidden');
            allAssignment.classList.add('hidden');

            // Show the selected assignment section
            if (this.value === 'user') {
                userAssignment.classList.remove('hidden');
            } else if (this.value === 'group') {
                groupAssignment.classList.remove('hidden');
            } else if (this.value === 'all') {
                allAssignment.classList.remove('hidden');
            }
        });
    });
</script>
@endif
@endsection
