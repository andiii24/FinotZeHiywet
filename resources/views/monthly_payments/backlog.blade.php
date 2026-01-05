@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Payment Backlog Details</h2>
                    <div class="space-x-2">
                        <a href="{{ route('monthly-payments.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Make Payment</a>
                        <a href="{{ route('monthly-payments.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to Payments</a>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm font-medium text-blue-600">Monthly Amount</div>
                        <div class="text-2xl font-bold text-blue-900">${{ number_format($backlog['monthly_amount'], 2) }}</div>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <div class="text-sm font-medium text-red-600">Total Owed</div>
                        <div class="text-2xl font-bold text-red-900">${{ number_format($backlog['total_owed'], 2) }}</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="text-sm font-medium text-yellow-600">Months Owed</div>
                        <div class="text-2xl font-bold text-yellow-900">{{ $backlog['months_owed'] }}</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-sm font-medium text-green-600">Total Paid</div>
                        <div class="text-2xl font-bold text-green-900">${{ number_format($backlog['total_paid'], 2) }}</div>
                    </div>
                </div>

                @if($backlog['total_owed'] > 0)
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Payment Required</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>You have outstanding payments for {{ $backlog['months_owed'] }} months totaling ${{ number_format($backlog['total_owed'], 2) }}.</p>
                                    <p class="mt-1">You can pay in full or make partial payments. Any overpayment will be applied to future months.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    <strong>Great!</strong> You are up to date with your monthly payments.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Monthly Details Table -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Monthly Payment Details</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Detailed breakdown of each month's payment status.</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Required Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Paid</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remaining</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($backlog['months_detail'] as $monthKey => $month)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $month['month'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ${{ number_format($month['amount'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ${{ number_format($month['paid'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($month['status'] === 'paid')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Paid
                                                    </span>
                                                @elseif($month['status'] === 'partial')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Partial
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Unpaid
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($month['status'] === 'paid')
                                                    <span class="text-green-600">$0.00</span>
                                                @else
                                                    <span class="text-red-600">${{ number_format($month['amount'] - $month['paid'], 2) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if($backlog['total_owed'] > 0)
                    <div class="mt-6 flex justify-center">
                        <a href="{{ route('monthly-payments.create') }}" class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition text-lg font-medium">
                            Make Payment Now
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
