<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Planning') }}: {{ $planning->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('plannings.show', $planning) }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    View Planning
                </a>
                <a href="{{ route('plannings.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Plannings
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('plannings.update', $planning) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $planning->title) }}" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-300 @enderror">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror">{{ old('description', $planning->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Objectives -->
                            <div class="md:col-span-2">
                                <label for="objectives" class="block text-sm font-medium text-gray-700">Objectives</label>
                                <textarea name="objectives" id="objectives" rows="4"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('objectives') border-red-300 @enderror"
                                          placeholder="List the main objectives for this planning...">{{ old('objectives', $planning->objectives) }}</textarea>
                                @error('objectives')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Timeframe Type -->
                            <div>
                                <label for="timeframe_type" class="block text-sm font-medium text-gray-700">Timeframe Type *</label>
                                <select name="timeframe_type" id="timeframe_type" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('timeframe_type') border-red-300 @enderror">
                                    <option value="">Select timeframe type</option>
                                    @foreach($timeframeTypes as $key => $value)
                                        <option value="{{ $key }}" {{ old('timeframe_type', $planning->timeframe_type) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('timeframe_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Priority Level -->
                            <div>
                                <label for="priority_level" class="block text-sm font-medium text-gray-700">Priority Level *</label>
                                <select name="priority_level" id="priority_level" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('priority_level') border-red-300 @enderror">
                                    <option value="">Select priority level</option>
                                    @foreach($priorityLevels as $key => $value)
                                        <option value="{{ $key }}" {{ old('priority_level', $planning->priority_level) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('priority_level')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date *</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $planning->start_date->format('Y-m-d')) }}" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('start_date') border-red-300 @enderror">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date *</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $planning->end_date->format('Y-m-d')) }}" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('end_date') border-red-300 @enderror">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Group Category -->
                            <div>
                                <label for="group_cat_id" class="block text-sm font-medium text-gray-700">Group Category *</label>
                                <select name="group_cat_id" id="group_cat_id" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('group_cat_id') border-red-300 @enderror">
                                    <option value="">Select group category</option>
                                    @foreach($groupCats as $groupCat)
                                        <option value="{{ $groupCat->id }}" {{ old('group_cat_id', $planning->group_cat_id) == $groupCat->id ? 'selected' : '' }}>
                                            {{ $groupCat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('group_cat_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                                <select name="status" id="status" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-300 @enderror">
                                    @foreach($statuses as $key => $value)
                                        <option value="{{ $key }}" {{ old('status', $planning->status) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Budget Amount -->
                            <div>
                                <label for="budget_amount" class="block text-sm font-medium text-gray-700">Budget Amount</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="budget_amount" id="budget_amount" value="{{ old('budget_amount', $planning->budget_amount) }}"
                                           step="0.01" min="0"
                                           class="pl-7 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('budget_amount') border-red-300 @enderror">
                                </div>
                                @error('budget_amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Group List -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Additional Groups</label>
                                <div class="mt-2 space-y-2">
                                    @foreach($groupCats as $groupCat)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="group_list[]" value="{{ $groupCat->id }}"
                                                   {{ in_array($groupCat->id, old('group_list', $planning->group_list ?? [])) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">{{ $groupCat->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('group_list')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Public Visibility -->
                            <div class="md:col-span-2">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_public" id="is_public" value="1"
                                           {{ old('is_public', $planning->is_public) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="is_public" class="ml-2 block text-sm text-gray-700">
                                        Make this planning visible to all users
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('plannings.show', $planning) }}"
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Update Planning
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-update end date based on timeframe type
        document.getElementById('timeframe_type').addEventListener('change', function() {
            const startDate = document.getElementById('start_date').value;
            if (startDate && this.value) {
                const start = new Date(startDate);
                let endDate = new Date(start);

                switch(this.value) {
                    case 'monthly':
                        endDate.setMonth(endDate.getMonth() + 1);
                        break;
                    case 'quarterly':
                        endDate.setMonth(endDate.getMonth() + 3);
                        break;
                    case 'yearly':
                        endDate.setFullYear(endDate.getFullYear() + 1);
                        break;
                }

                document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
            }
        });
    </script>
</x-app-layout>
