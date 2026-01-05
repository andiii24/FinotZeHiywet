<?php

namespace App\Http\Controllers;

use App\Models\Monthly_Payment;
use App\Models\MonthlyPaymentSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MonthlyPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user is admin to see all payments or just their own
        if (Auth::user()->isAdmin()) {
            $payments = Monthly_Payment::with('user')->orderBy('created_at', 'desc')->paginate(10);
            $backlog = null;
        } else {
            $payments = Auth::user()->monthlyPayments()->orderBy('created_at', 'desc')->paginate(10);
            $backlog = MonthlyPaymentSetting::calculatePaymentBacklog(Auth::user());
        }

        return view('monthly_payments.index', compact('payments', 'backlog'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // If admin, show dropdown to select user
        $users = Auth::user()->isAdmin() ? User::all() : null;

        // Get current payment settings
        $settings = MonthlyPaymentSetting::getCurrentSettings();

        // Get payment backlog for current user
        $backlog = MonthlyPaymentSetting::calculatePaymentBacklog(Auth::user());

        // Get next payment month
        $nextMonth = MonthlyPaymentSetting::getNextPaymentMonth(Auth::user());

        // Get next unpaid month for payment
        $nextUnpaidMonth = $this->getNextUnpaidMonth(Auth::user());

        return view('monthly_payments.create', compact('users', 'settings', 'backlog', 'nextMonth', 'nextUnpaidMonth'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string',
            'user_id' => Auth::user()->isAdmin() ? 'required|exists:users,id' : '',
        ]);

        $data = $request->except('image');

        // If not admin, set user_id to current user
        if (!Auth::user()->isAdmin()) {
            $data['user_id'] = Auth::id();
        }

        // Get the user for this payment
        $user = Auth::user()->isAdmin() ? User::find($data['user_id']) : Auth::user();

        // Check if user has backlog (for both admin and regular users)
        $backlog = MonthlyPaymentSetting::calculatePaymentBacklog($user);

        // Handle automatic payment allocation for backlog
        if ($data['month'] === 'backlog' || ($backlog['total_owed'] > 0 && $data['month'] !== 'backlog')) {
            // Force backlog processing if user has outstanding balance
            $data['month'] = 'backlog';
            $this->processBacklogPayment($user, $data, $request);
        } else {
            // Regular payment for specific month
            $data['required_amount'] = MonthlyPaymentSetting::getAmountForUser($user);

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('payment_receipts', 'public');
                $data['image'] = $imagePath;
            }

            Monthly_Payment::create($data);
        }

        return redirect()->route('monthly-payments.index')
            ->with('success', 'Monthly payment record created successfully.');
    }

    /**
     * Process payment for backlog (automatic allocation to oldest unpaid months)
     */
    private function processBacklogPayment($user, $data, $request)
    {
        $backlog = MonthlyPaymentSetting::calculatePaymentBacklog($user);
        $paymentAmount = $data['amount'];

        // Create a single payment record with the total amount
        $paymentData = [
            'user_id' => $data['user_id'],
            'month' => 'backlog', // Mark as backlog payment
            'amount' => $paymentAmount, // Store the actual amount entered
            'required_amount' => $backlog['total_owed'], // Store the total required amount
            'payment_method' => $data['payment_method'],
            'notes' => $data['notes'] . ' (Backlog payment - will be allocated to oldest unpaid months)',
            'status' => 'pending'
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('payment_receipts', 'public');
            $paymentData['image'] = $imagePath;
        }

        Monthly_Payment::create($paymentData);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Monthly_Payment::with('user')->findOrFail($id);

        // Check if user has permission to view this payment
        if (!Auth::user()->isAdmin() && $payment->user_id !== Auth::id()) {
            return redirect()->route('monthly-payments.index')
                ->with('error', 'You do not have permission to view this payment.');
        }

        return view('monthly_payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payment = Monthly_Payment::findOrFail($id);

        // Check if user has permission to edit this payment
        if (!Auth::user()->isAdmin() && $payment->user_id !== Auth::id()) {
            return redirect()->route('monthly-payments.index')
                ->with('error', 'You do not have permission to edit this payment.');
        }

        // If admin, get all users for dropdown
        $users = Auth::user()->isAdmin() ? User::all() : null;

        return view('monthly_payments.edit', compact('payment', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = Monthly_Payment::findOrFail($id);

        // Check if user has permission to update this payment
        if (!Auth::user()->isAdmin() && $payment->user_id !== Auth::id()) {
            return redirect()->route('monthly-payments.index')
                ->with('error', 'You do not have permission to update this payment.');
        }

        $request->validate([
            'month' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string',
            'user_id' => Auth::user()->isAdmin() ? 'required|exists:users,id' : '',
            'status' => Auth::user()->isAdmin() ? 'required|in:pending,approved,rejected' : '',
        ]);

        $data = $request->except(['image', '_token', '_method']);

        // If not admin, don't allow changing user_id or status
        if (!Auth::user()->isAdmin()) {
            unset($data['user_id']);
            unset($data['status']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($payment->image) {
                Storage::disk('public')->delete($payment->image);
            }

            $imagePath = $request->file('image')->store('payment_receipts', 'public');
            $data['image'] = $imagePath;
        }

        $payment->update($data);

        return redirect()->route('monthly-payments.index')
            ->with('success', 'Payment record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Monthly_Payment::findOrFail($id);

        // Only admin can delete payments
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('monthly-payments.index')
                ->with('error', 'You do not have permission to delete payment records.');
        }

        // Delete image if exists
        if ($payment->image) {
            Storage::disk('public')->delete($payment->image);
        }

        $payment->delete();

        return redirect()->route('monthly-payments.index')
            ->with('success', 'Payment record deleted successfully.');
    }

    /**
     * Update payment status (admin only).
     */
    public function updateStatus(Request $request, string $id)
    {
        // Only admin can change status
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('monthly-payments.index')
                ->with('error', 'You do not have permission to update payment status.');
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $payment = Monthly_Payment::findOrFail($id);
        $oldStatus = $payment->status;
        $payment->status = $request->status;
        $payment->save();

        // If payment is being approved, update the user's payment backlog
        if ($request->status === 'approved' && $oldStatus !== 'approved') {
            $this->updateUserPaymentBacklog($payment->user);
        }

        return redirect()->route('monthly-payments.show', $payment->id)
            ->with('success', 'Payment status updated successfully.');
    }

    /**
     * Update user's payment backlog after a payment is approved
     */
    private function updateUserPaymentBacklog($user)
    {
        // Force recalculation of backlog by clearing any cached data
        // The backlog calculation will automatically use the latest approved payments
        $backlog = MonthlyPaymentSetting::calculatePaymentBacklog($user);

        // Log the update for debugging
        \Log::info("Payment approved for user {$user->id}. New backlog: {$backlog['total_owed']} owed, {$backlog['months_owed']} months");
    }

    /**
     * Show payment backlog details for the current user.
     */
    public function backlog()
    {
        $backlog = MonthlyPaymentSetting::calculatePaymentBacklog(Auth::user());

        return view('monthly_payments.backlog', compact('backlog'));
    }

    /**
     * Get user backlog information for admin (AJAX endpoint).
     */
    public function getUserBacklog($userId)
    {
        // Only admin can access this
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($userId);
        $backlog = MonthlyPaymentSetting::calculatePaymentBacklog($user);

        // Ensure numeric values are properly formatted
        $backlog['total_owed'] = (float) $backlog['total_owed'];
        $backlog['monthly_amount'] = (float) $backlog['monthly_amount'];
        $backlog['total_paid'] = (float) $backlog['total_paid'];

        return response()->json([
            'backlog' => $backlog,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'work_status' => $user->work_status
            ]
        ]);
    }

    /**
     * Get the next unpaid month for payment
     */
    private function getNextUnpaidMonth($user)
    {
        $backlog = MonthlyPaymentSetting::calculatePaymentBacklog($user);

        // If user has backlog, return null (will use backlog processing)
        if ($backlog['total_owed'] > 0) {
            return null;
        }

        // If user is paid ahead, return the month after their last paid month
        if (isset($backlog['last_paid_month']) && $backlog['last_paid_month']) {
            $lastPaidDate = \Carbon\Carbon::parse($backlog['last_paid_month'] . '-01');
            return $lastPaidDate->addMonth()->format('Y-m');
        }

        // If no last paid month, return next month
        return now()->addMonth()->format('Y-m');
    }
}
