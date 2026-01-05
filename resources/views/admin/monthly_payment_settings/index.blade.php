@extends('admin.layouts.app')

@section('title', 'Monthly Payment Settings')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Monthly Payment Settings</h2>
            <a href="{{ route('admin.monthly-payment-settings.edit') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Edit Settings</a>
        </div>

        @if ($message = Session::get('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Current Payment Amounts</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Configure the monthly payment amounts for employed and unemployed users.</p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Employed Users Amount</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="text-2xl font-bold text-green-600">${{ number_format($settings->employed_amount, 2) }}</span>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Unemployed Users Amount</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="text-2xl font-bold text-blue-600">${{ number_format($settings->unemployed_amount, 2) }}</span>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Payment Start Month</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="text-lg font-semibold text-purple-600">{{ $settings->start_month ? $settings->start_month->format('F Y') : 'Not Set' }}</span>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($settings->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $settings->updated_at->format('F d, Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Note:</strong> These amounts will be automatically applied to new monthly payment records based on each user's employment status.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
