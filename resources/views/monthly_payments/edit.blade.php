@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Edit Monthly Payment</h2>
                    <div class="space-x-2">
                        <a href="{{ route('monthly-payments.show', $payment->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">View Details</a>
                        <a href="{{ route('monthly-payments.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to List</a>
                    </div>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Required Amount:</strong> ${{ number_format($payment->required_amount, 2) }}
                                @if($payment->user->work_status)
                                    (Employed User)
                                @else
                                    (Unemployed User)
                                @endif
                            </p>
                        </div>
                    </div>
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

                <form action="{{ route('monthly-payments.update', $payment->id) }}" method="POST" enctype="multipart/form-data" class="mt-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if(Auth::user()->isAdmin() && isset($users))
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                                <select id="user_id" name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $payment->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div>
                            <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
                            <input type="month" name="month" id="month" value="{{ old('month', $payment->month) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount Paid</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" name="amount" id="amount" value="{{ old('amount', $payment->amount) }}" class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Enter the amount you are paying</p>
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <select id="payment_method" name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">Select Payment Method</option>
                                <option value="Cash" {{ old('payment_method', $payment->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Bank Transfer" {{ old('payment_method', $payment->payment_method) == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="Credit Card" {{ old('payment_method', $payment->payment_method) == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                                <option value="Mobile Payment" {{ old('payment_method', $payment->payment_method) == 'Mobile Payment' ? 'selected' : '' }}>Mobile Payment</option>
                                <option value="Other" {{ old('payment_method', $payment->payment_method) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        @if(Auth::user()->isAdmin())
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <option value="pending" {{ old('status', $payment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ old('status', $payment->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ old('status', $payment->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                        @endif

                        <div class="md:col-span-2">
                            <label for="image" class="block text-sm font-medium text-gray-700">Receipt Image (optional)</label>
                            <input type="file" name="image" id="image" class="mt-1 block w-full">
                            <p class="mt-1 text-sm text-gray-500">Upload a receipt image (JPG, PNG, GIF up to 2MB)</p>

                            @if($payment->image)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Current image:</p>
                                    <img src="{{ asset('storage/' . $payment->image) }}" alt="Current Receipt" class="mt-1 h-32 rounded-md shadow-sm">
                                </div>
                            @endif
                        </div>

                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes (optional)</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $payment->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:opacity-90 transition">Update Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
