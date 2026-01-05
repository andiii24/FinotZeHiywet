@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">System Settings</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

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

    <form method="POST" action="{{ route('admin.system-settings.update') }}" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Application Name</label>
            <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name']) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Support Email</label>
            <input type="email" name="support_email" value="{{ old('support_email', $settings['support_email']) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Maintenance Mode</label>
            <select name="maintenance_mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="off" @selected(old('maintenance_mode', $settings['maintenance_mode'])==='off')>Off</option>
                <option value="on" @selected(old('maintenance_mode', $settings['maintenance_mode'])==='on')>On</option>
            </select>
            <p class="text-xs text-gray-500 mt-1">When on, non-admins will see a maintenance notice.</p>
        </div>

        <div class="pt-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Save Settings</button>
        </div>
    </form>
</div>
@endsection


