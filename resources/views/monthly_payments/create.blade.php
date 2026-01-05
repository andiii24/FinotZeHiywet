@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Add New Monthly Payment</h2>
                    <a href="{{ route('monthly-payments.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Back to List</a>
                </div>

                @if(isset($backlog))
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Payment Summary</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p><strong>Monthly Amount:</strong> ${{ number_format($backlog['monthly_amount'], 2) }}
                                        @if(Auth::user()->work_status)
                                            (Employed User)
                                        @else
                                            (Unemployed User)
                                        @endif
                                    </p>
                                    <p><strong>Total Owed:</strong> ${{ number_format($backlog['total_owed'], 2) }} ({{ $backlog['months_owed'] }} months)</p>
                                    <p><strong>Total Paid:</strong> ${{ number_format($backlog['total_paid'], 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($backlog['months_owed'] > 0)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Payment Options</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p><strong>You have outstanding payments!</strong> Your payment will be automatically applied to the oldest unpaid months.</p>
                                        <p class="mt-1">You can pay in full (${{ number_format($backlog['total_owed'], 2) }}) or make partial payments. Any overpayment will be applied to future months automatically.</p>
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
                                    <h3 class="text-sm font-medium text-green-800">You're Up to Date!</h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <p>You have no outstanding payments. You can now make payments for specific future months.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Dynamic user backlog info for admin -->
                @if(Auth::user()->isAdmin())
                    <div id="user-backlog-info" class="hidden">
                        <!-- This will be populated by JavaScript -->
                    </div>
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

                <form action="{{ route('monthly-payments.store') }}" method="POST" enctype="multipart/form-data" class="mt-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if(Auth::user()->isAdmin() && isset($users))
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                                <select id="user_id" name="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" onchange="loadUserBacklog(this.value)">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

    @if(!isset($backlog) || $backlog['total_owed'] == 0)
        @if(isset($backlog['months_ahead']) && $backlog['months_ahead'] > 0)
            <div class="bg-blue-50 p-4 rounded-md mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Payment Ahead</h3>
                        <p class="text-sm text-blue-700 mt-1">
                            You are already paid up until <strong>{{ $backlog['paid_until'] }}</strong>
                            ({{ $backlog['months_ahead'] }} month(s) ahead).
                        </p>
                        @if($backlog['overpayment'] > 0)
                            <p class="text-sm text-blue-600 mt-1">
                                Overpayment: <strong>${{ number_format($backlog['overpayment'], 2) }}</strong>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        <div>
            <label for="month" class="block text-sm font-medium text-gray-700">Payment Month</label>
            @if(isset($nextUnpaidMonth) && $nextUnpaidMonth)
                <div class="mt-1 p-3 bg-blue-50 border border-blue-200 rounded-md">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-blue-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-blue-800">
                            {{ \Carbon\Carbon::parse($nextUnpaidMonth . '-01')->format('F Y') }}
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-blue-600">This payment will be applied to the next unpaid month</p>
                </div>
                <input type="hidden" name="month" value="{{ $nextUnpaidMonth }}">
            @else
                                <input type="month" name="month" id="month" value="{{ old('month', $nextMonth ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                <p class="mt-1 text-sm text-gray-500">Select the month for this payment</p>
            @endif
        </div>
    @else
        <div class="bg-blue-50 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Automatic Payment Allocation</h3>
                    <p class="text-sm text-blue-700 mt-1">
                        Your payment will be automatically applied to the oldest unpaid months.
                        No need to select a specific month until you're caught up.
                    </p>
                </div>
            </div>
        </div>
        <input type="hidden" name="month" value="backlog">
    @endif

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount Paid</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" min="0" name="amount" id="amount" value="{{ old('amount') }}" class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" required>
                            </div>
                            <div class="mt-2 flex space-x-2" id="quick-payment-buttons">
                                @if(isset($backlog) && $backlog['total_owed'] > 0)
                                    <button type="button" onclick="document.getElementById('amount').value = '{{ $backlog['total_owed'] }}'" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition text-sm">
                                        Pay All Outstanding (${{ number_format($backlog['total_owed'], 2) }})
                                    </button>
                                    <button type="button" onclick="document.getElementById('amount').value = '{{ $backlog['monthly_amount'] }}'" class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 transition text-sm">
                                        Pay One Month (${{ number_format($backlog['monthly_amount'], 2) }})
                                    </button>
                                @elseif(isset($backlog))
                                    <button type="button" onclick="document.getElementById('amount').value = '{{ $backlog['monthly_amount'] }}'" class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 transition text-sm">
                                        Pay This Month (${{ number_format($backlog['monthly_amount'], 2) }})
                                    </button>
                                @endif
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Enter the amount you are paying</p>
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <select id="payment_method" name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">Select Payment Method</option>
                                <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="Credit Card" {{ old('payment_method') == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                                <option value="Mobile Payment" {{ old('payment_method') == 'Mobile Payment' ? 'selected' : '' }}>Mobile Payment</option>
                                <option value="Other" {{ old('payment_method') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="image" class="block text-sm font-medium text-gray-700">Receipt Image (optional)</label>
                            <input type="file" name="image" id="image" class="mt-1 block w-full">
                            <p class="mt-1 text-sm text-gray-500">Upload a receipt image (JPG, PNG, GIF up to 2MB)</p>
                        </div>

                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes (optional)</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:opacity-90 transition">Submit Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->isAdmin())
<script>
function loadUserBacklog(userId) {
    if (!userId) {
        document.getElementById('user-backlog-info').classList.add('hidden');
        return;
    }

    // Show loading state
    const backlogDiv = document.getElementById('user-backlog-info');
    backlogDiv.innerHTML = '<div class="bg-blue-50 p-4 rounded-md"><p class="text-blue-700">Loading user payment information...</p></div>';
    backlogDiv.classList.remove('hidden');

    // Fetch user backlog via AJAX
    fetch(`/admin/user-backlog/${userId}`)
        .then(response => response.json())
        .then(data => {
            console.log('User backlog data:', data); // Debug log

            // Validate data structure
            if (!data.backlog) {
                throw new Error('Invalid response: missing backlog data');
            }

            if (data.backlog && data.backlog.total_owed > 0) {
                // User has backlog
                backlogDiv.innerHTML = `
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">User Payment Backlog</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p><strong>Total Owed:</strong> $${parseFloat(data.backlog.total_owed).toFixed(2)} (${data.backlog.months_owed} months)</p>
                                    <p><strong>Monthly Amount:</strong> $${parseFloat(data.backlog.monthly_amount).toFixed(2)}</p>
                                    <p><strong>Total Paid:</strong> $${parseFloat(data.backlog.total_paid).toFixed(2)}</p>
                                    <p class="mt-2 text-yellow-700"><strong>Note:</strong> Payment will be automatically applied to oldest unpaid months.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Hide month selection and show backlog message
                const monthDiv = document.querySelector('div:has(#month)');
                if (monthDiv) {
                    monthDiv.innerHTML = `
                        <div class="bg-blue-50 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Automatic Payment Allocation</h3>
                                    <p class="text-sm text-blue-700 mt-1">
                                        Payment will be automatically applied to the oldest unpaid months.
                                        No need to select a specific month.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="month" value="backlog">
                    `;
                }

                // Update quick payment buttons
                const quickButtonsDiv = document.getElementById('quick-payment-buttons');
                if (quickButtonsDiv) {
                    quickButtonsDiv.innerHTML = `
                        <button type="button" onclick="document.getElementById('amount').value = '${data.backlog.total_owed}'" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition text-sm">
                            Pay All Outstanding ($${parseFloat(data.backlog.total_owed).toFixed(2)})
                        </button>
                        <button type="button" onclick="document.getElementById('amount').value = '${data.backlog.monthly_amount}'" class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 transition text-sm">
                            Pay One Month ($${parseFloat(data.backlog.monthly_amount).toFixed(2)})
                        </button>
                    `;
                }
            } else {
                // User is up to date
                backlogDiv.innerHTML = `
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    <strong>User is up to date!</strong> No outstanding payments. You can select a specific month for future payment.
                                </p>
                            </div>
                        </div>
                    </div>
                `;

                    // Show next unpaid month
                    const monthDiv = document.querySelector('div:has(#month)');
                    if (monthDiv) {
                        // Calculate next unpaid month
                        let nextUnpaidMonth = null;
                        if (data.backlog.last_paid_month) {
                            const lastPaidDate = new Date(data.backlog.last_paid_month + '-01');
                            const nextMonth = new Date(lastPaidDate.getFullYear(), lastPaidDate.getMonth() + 1, 1);
                            nextUnpaidMonth = nextMonth.toISOString().slice(0, 7);
                        } else {
                            const currentDate = new Date();
                            const nextMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1);
                            nextUnpaidMonth = nextMonth.toISOString().slice(0, 7);
                        }

                        const monthLabel = new Date(nextUnpaidMonth + '-01').toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

                        monthDiv.innerHTML = `
                            <label for="month" class="block text-sm font-medium text-gray-700">Payment Month</label>
                            <div class="mt-1 p-3 bg-blue-50 border border-blue-200 rounded-md">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-blue-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-medium text-blue-800">${monthLabel}</span>
                                </div>
                                <p class="mt-1 text-xs text-blue-600">This payment will be applied to the next unpaid month</p>
                            </div>
                            <input type="hidden" name="month" value="${nextUnpaidMonth}">
                        `;
                    }

                // Update quick payment buttons for up-to-date user
                const quickButtonsDiv = document.getElementById('quick-payment-buttons');
                if (quickButtonsDiv) {
                    quickButtonsDiv.innerHTML = `
                        <button type="button" onclick="document.getElementById('amount').value = '${data.backlog.monthly_amount}'" class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 transition text-sm">
                            Pay This Month ($${parseFloat(data.backlog.monthly_amount).toFixed(2)})
                        </button>
                    `;
                }
            }
        })
        .catch(error => {
            console.error('Error loading user backlog:', error);
            backlogDiv.innerHTML = '<div class="bg-red-50 p-4 rounded-md"><p class="text-red-700">Error loading user information.</p></div>';
        });
}
</script>
@endif
@endsection
