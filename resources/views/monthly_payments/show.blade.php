@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Payment Details</h2>
                    <a href="{{ route('monthly-payments.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to List</a>
                </div>

                @if ($message = Session::get('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Payment #{{ $payment->id }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Payment details and information.</p>
                        </div>
                        <div>
                            @if(Auth::user()->isAdmin() || Auth::id() == $payment->user_id)
                                <a href="{{ route('monthly-payments.edit', $payment->id) }}" class="px-3 py-1 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 transition">Edit</a>
                            @endif
                        </div>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">User</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $payment->user->name }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Month</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $payment->month }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Required Amount</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span class="text-lg font-semibold text-blue-600">${{ number_format($payment->required_amount, 2) }}</span>
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Amount Paid</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span class="text-lg font-semibold {{ $payment->amount >= $payment->required_amount ? 'text-green-600' : 'text-red-600' }}">
                                        ${{ number_format($payment->amount, 2) }}
                                    </span>
                                    @if($payment->amount < $payment->required_amount)
                                        <span class="text-sm text-red-500 block mt-1">Underpaid by ${{ number_format($payment->required_amount - $payment->amount, 2) }}</span>
                                    @elseif($payment->amount > $payment->required_amount)
                                        <span class="text-sm text-green-500 block mt-1">Overpaid by ${{ number_format($payment->amount - $payment->required_amount, 2) }}</span>
                                    @else
                                        <span class="text-sm text-green-500 block mt-1">Exact amount paid</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $payment->payment_method }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                                    @if($payment->status == 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($payment->status == 'rejected')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif

                                    @if(Auth::user()->isAdmin())
                                        <div class="mt-2">
                                            <form action="{{ route('monthly-payments.status', $payment->id) }}" method="POST" class="inline-flex space-x-2">
                                                @csrf
                                                <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                    <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="approved" {{ $payment->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="rejected" {{ $payment->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                                <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition text-sm">
                                                    Update Status
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $payment->notes ?? 'No notes provided' }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Receipt Image</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @if($payment->image)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $payment->image) }}" target="_blank" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                                                View Receipt
                                            </a>
                                            <img src="{{ asset('storage/' . $payment->image) }}" alt="Receipt" class="mt-4 max-w-md rounded-lg shadow-md">
                                        </div>
                                    @else
                                        No receipt image uploaded
                                    @endif
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $payment->created_at->format('F d, Y H:i:s') }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $payment->updated_at->format('F d, Y H:i:s') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
