@extends('admin.layouts.app')

@section('title', 'Create User')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Create New User</h2>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to List</a>
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

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="{{ \App\Models\User::ROLE_USER }}" {{ old('role') == \App\Models\User::ROLE_USER ? 'selected' : '' }}>User</option>
                        <option value="{{ \App\Models\User::ROLE_ADMIN }}" {{ old('role') == \App\Models\User::ROLE_ADMIN ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div>
                    <label for="group_cat_id" class="block text-sm font-medium text-gray-700">Group Category</label>
                    <select name="group_cat_id" id="group_cat_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">None</option>
                        @foreach($groupCats as $groupCat)
                            <option value="{{ $groupCat->id }}" {{ old('group_cat_id') == $groupCat->id ? 'selected' : '' }}>{{ $groupCat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="marital_status" class="block text-sm font-medium text-gray-700">Marital Status</label>
                    <select name="marital_status" id="marital_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Marital Status</option>
                        <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                        <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                    </select>
                </div>

                <div>
                    <label for="education_background" class="block text-sm font-medium text-gray-700">Education Background</label>
                    <input type="text" name="education_background" id="education_background" value="{{ old('education_background') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="work_status" id="work_status" value="1" {{ old('work_status') ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="work_status" class="font-medium text-gray-700">Currently Working</label>
                    </div>
                </div>

                <div id="work_details" class="{{ old('work_status') ? '' : 'hidden' }} space-y-4">
                    <div>
                        <label for="job_title" class="block text-sm font-medium text-gray-700">Job Title</label>
                        <input type="text" name="job_title" id="job_title" value="{{ old('job_title') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="work_place" class="block text-sm font-medium text-gray-700">Work Place</label>
                        <input type="text" name="work_place" id="work_place" value="{{ old('work_place') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <label for="skills_input" class="block text-sm font-medium text-gray-700 mb-2">Skills</label>
                <div class="bg-gray-50 p-4 rounded-md">
                    <div>
                        <input type="text" name="skills_input" id="skills_input" value="{{ old('skills_input') }}"
                            placeholder="Enter skills separated by commas (e.g., Programming, Design, Marketing)"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="text-sm text-gray-500 mt-1">Press Enter or add a comma after each skill</p>
                        <div id="skills_tags" class="flex flex-wrap gap-2 mt-2"></div>
                        <input type="hidden" name="skills_array" id="skills_array" value="{{ old('skills_array') }}">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Create User</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle work status toggle
        const workStatusCheckbox = document.getElementById('work_status');
        const workDetailsDiv = document.getElementById('work_details');

        if (workStatusCheckbox && workDetailsDiv) {
            workStatusCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    workDetailsDiv.classList.remove('hidden');
                } else {
                    workDetailsDiv.classList.add('hidden');
                }
            });
        }

        // Handle skills input
        const skillsInput = document.getElementById('skills_input');
        const skillsArray = document.getElementById('skills_array');
        const skillsTags = document.getElementById('skills_tags');

        let skills = [];

        // Initialize from any existing value
        if (skillsArray && skillsArray.value) {
            skills = JSON.parse(skillsArray.value);
            renderTags();
        }

        if (skillsInput) {
            // Handle input events
            skillsInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    addSkill();
                }
            });

            skillsInput.addEventListener('blur', function() {
                addSkill();
            });
        }

        function addSkill() {
            const value = skillsInput.value.trim();
            if (value && !value.endsWith(',')) {
                // Add the skill if it's not empty and doesn't end with a comma
                const skillsToAdd = value.split(',').map(s => s.trim()).filter(s => s);

                skillsToAdd.forEach(skill => {
                    if (skill && !skills.includes(skill)) {
                        skills.push(skill);
                    }
                });

                skillsInput.value = '';
                updateSkillsArray();
                renderTags();
            } else if (value.endsWith(',')) {
                // If it ends with a comma, remove the comma and process
                skillsInput.value = value.slice(0, -1);
                addSkill();
            }
        }

        function updateSkillsArray() {
            if (skillsArray) {
                skillsArray.value = JSON.stringify(skills);
            }
        }

        function renderTags() {
            if (!skillsTags) return;

            skillsTags.innerHTML = '';

            skills.forEach((skill, index) => {
                const tag = document.createElement('div');
                tag.className = 'bg-indigo-100 text-indigo-800 text-sm px-2 py-1 rounded-md flex items-center';
                tag.innerHTML = `
                    <span>${skill}</span>
                    <button type="button" class="ml-1 text-indigo-600 hover:text-indigo-900 focus:outline-none" data-index="${index}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                `;

                const removeButton = tag.querySelector('button');
                removeButton.addEventListener('click', function() {
                    const index = parseInt(this.dataset.index);
                    skills.splice(index, 1);
                    updateSkillsArray();
                    renderTags();
                });

                skillsTags.appendChild(tag);
            });
        }
    });
</script>
@endsection
