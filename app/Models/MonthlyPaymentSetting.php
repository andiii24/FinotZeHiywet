<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyPaymentSetting extends Model
{
    protected $fillable = [
        'employed_amount',
        'unemployed_amount',
        'start_month',
        'is_active',
    ];

    protected $casts = [
        'employed_amount' => 'decimal:2',
        'unemployed_amount' => 'decimal:2',
        'start_month' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the current active settings
     */
    public static function getCurrentSettings()
    {
        return self::where('is_active', true)->first() ?? self::first();
    }

    /**
     * Get the amount for a user based on their employment status
     */
    public static function getAmountForUser($user)
    {
        $settings = self::getCurrentSettings();

        if (!$settings) {
            return 0;
        }

        // Check if user is currently working
        if ($user->work_status) {
            return $settings->employed_amount;
        }

        return $settings->unemployed_amount;
    }

    /**
     * Calculate payment backlog for a user
     */
    public static function calculatePaymentBacklog($user)
    {
        $settings = self::getCurrentSettings();

        if (!$settings || !$settings->start_month) {
            return [
                'total_owed' => 0,
                'months_owed' => 0,
                'monthly_amount' => 0,
                'months_detail' => []
            ];
        }

        $startMonth = $settings->start_month;
        $currentMonth = now()->startOfMonth();
        $monthlyAmount = self::getAmountForUser($user);

        // Get all approved payments made by the user
        $payments = $user->monthlyPayments()
            ->where('status', 'approved')
            ->orderBy('created_at')
            ->get();

        $totalPaid = $payments->sum('amount');
        $monthsDetail = [];
        $currentDate = \Carbon\Carbon::parse($startMonth);
        $totalOwed = 0;
        $monthsOwed = 0;

        // Calculate months from start to current
        while ($currentDate->lte($currentMonth)) {
            $monthKey = $currentDate->format('Y-m');
            $monthsDetail[$monthKey] = [
                'month' => $currentDate->format('F Y'),
                'amount' => $monthlyAmount,
                'paid' => 0,
                'status' => 'unpaid'
            ];

            $totalOwed += $monthlyAmount;
            $monthsOwed++;
            $currentDate->addMonth();
        }

        // Apply payments to months (oldest first)
        $remainingPayment = $totalPaid;
        foreach ($monthsDetail as $monthKey => &$month) {
            if ($remainingPayment <= 0) break;

            if ($remainingPayment >= $month['amount']) {
                $month['paid'] = $month['amount'];
                $month['status'] = 'paid';
                $remainingPayment -= $month['amount'];
                $totalOwed -= $month['amount'];
                $monthsOwed--;
            } else {
                $month['paid'] = $remainingPayment;
                $month['status'] = 'partial';
                $totalOwed -= $remainingPayment;
                $remainingPayment = 0;
            }
        }

        // Calculate overpayment and months ahead
        $overpayment = max(0, $remainingPayment);
        $monthsAhead = 0;
        $lastPaidMonth = null;
        $paidUntil = null;

        if ($overpayment > 0) {
            $monthsAhead = floor($overpayment / $monthlyAmount);
            if ($monthsAhead > 0) {
                $lastPaidMonth = $currentMonth->copy()->addMonths($monthsAhead)->format('Y-m');
                $paidUntil = \Carbon\Carbon::parse($lastPaidMonth . '-01')->format('F Y');
            }
        } else {
            // If no overpayment, find the last paid month
            foreach (array_reverse($monthsDetail, true) as $monthKey => $month) {
                if ($month['status'] === 'paid') {
                    $lastPaidMonth = $monthKey;
                    $paidUntil = \Carbon\Carbon::parse($monthKey . '-01')->format('F Y');
                    break;
                }
            }
        }

        return [
            'total_owed' => max(0, $totalOwed),
            'months_owed' => max(0, $monthsOwed),
            'monthly_amount' => $monthlyAmount,
            'months_detail' => $monthsDetail,
            'total_paid' => $totalPaid,
            'overpayment' => $overpayment,
            'months_ahead' => $monthsAhead,
            'last_paid_month' => $lastPaidMonth,
            'paid_until' => $paidUntil
        ];
    }

    /**
     * Get the next month that needs payment
     */
    public static function getNextPaymentMonth($user)
    {
        $backlog = self::calculatePaymentBacklog($user);

        foreach ($backlog['months_detail'] as $monthKey => $month) {
            if ($month['status'] !== 'paid') {
                return $monthKey;
            }
        }

        // If all months are paid, return next month
        return now()->addMonth()->format('Y-m');
    }
}
