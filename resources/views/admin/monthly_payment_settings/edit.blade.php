@extends('admin.layouts.app')

@section('title', 'Edit Monthly Payment Settings')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Edit Monthly Payment Settings</h2>
            <a href="{{ route('admin.monthly-payment-settings.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to Settings</a>
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

        <form action="{{ route('admin.monthly-payment-settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Payment Amounts</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Set the monthly payment amounts for different user types.</p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="employed_amount" class="block text-sm font-medium text-gray-700">Employed Users Amount</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" name="employed_amount" id="employed_amount"
                                       value="{{ old('employed_amount', $settings->employed_amount) }}"
                                       class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Amount for users who are currently employed.</p>
                        </div>

                        <div>
                            <label for="unemployed_amount" class="block text-sm font-medium text-gray-700">Unemployed Users Amount</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" name="unemployed_amount" id="unemployed_amount"
                                       value="{{ old('unemployed_amount', $settings->unemployed_amount) }}"
                                       class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Amount for users who are not currently employed.</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="start_month" class="block text-sm font-medium text-gray-700">Payment Start Month</label>
                            <input type="month" name="start_month" id="start_month"
                                   value="{{ old('start_month', $settings->start_month ? $settings->start_month->format('Y-m') : '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            <p class="mt-2 text-sm text-gray-500">The month from which monthly payments started. All users will owe payments from this month onwards.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.monthly-payment-settings.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Update Settings</button>
            </div>
        </form>
    </div>
</div>
@endsection
