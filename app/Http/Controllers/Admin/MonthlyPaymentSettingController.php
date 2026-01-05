<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyPaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthlyPaymentSettingController extends Controller
{
    /**
     * Display the current settings.
     */
    public function index()
    {
        $settings = MonthlyPaymentSetting::getCurrentSettings();

        return view('admin.monthly_payment_settings.index', compact('settings'));
    }

    /**
     * Show the form for editing the settings.
     */
    public function edit()
    {
        $settings = MonthlyPaymentSetting::getCurrentSettings();

        return view('admin.monthly_payment_settings.edit', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'employed_amount' => 'required|numeric|min:0',
            'unemployed_amount' => 'required|numeric|min:0',
            'start_month' => 'required|date',
        ]);

        $settings = MonthlyPaymentSetting::getCurrentSettings();

        if ($settings) {
            $settings->update($request->only(['employed_amount', 'unemployed_amount', 'start_month']));
        } else {
            MonthlyPaymentSetting::create([
                'employed_amount' => $request->employed_amount,
                'unemployed_amount' => $request->unemployed_amount,
                'start_month' => $request->start_month,
                'is_active' => true,
            ]);
        }

        return redirect()->route('admin.monthly-payment-settings.index')
            ->with('success', 'Monthly payment settings updated successfully.');
    }
}
