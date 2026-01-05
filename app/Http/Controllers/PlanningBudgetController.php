<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use App\Models\PlanningBudget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PlanningBudgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of budget records for a planning.
     */
    public function index(Planning $planning)
    {
        // Check access
        if (!$planning->is_public &&
            $planning->created_by !== Auth::id() &&
            !$planning->assignedUsers()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You do not have access to this planning.');
        }

        $budgetRecords = $planning->budgetRecords()->with('creator')->orderBy('date', 'desc')->get();

        // Calculate budget summary
        $totalIncome = $budgetRecords->where('budget_type', 'income')->sum('amount');
        $totalExpense = $budgetRecords->where('budget_type', 'expense')->sum('amount');
        $remainingBudget = $planning->budget_amount + $totalIncome - $totalExpense;

        return response()->json([
            'budget_records' => $budgetRecords,
            'budget_summary' => [
                'total_budget' => $planning->budget_amount,
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'remaining_budget' => $remainingBudget,
                'spent_percentage' => $planning->budget_amount > 0 ? ($totalExpense / $planning->budget_amount) * 100 : 0,
            ]
        ]);
    }

    /**
     * Store a newly created budget record.
     */
    public function store(Request $request, Planning $planning)
    {
        // Check access
        if (!$planning->is_public &&
            $planning->created_by !== Auth::id() &&
            !$planning->assignedUsers()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You do not have access to this planning.');
        }

        $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'budget_type' => 'required|in:income,expense',
            'date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'category.required' => 'The category field is required.',
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.01.',
            'budget_type.required' => 'The budget type field is required.',
            'budget_type.in' => 'The budget type must be either income or expense.',
            'date.required' => 'The date field is required.',
            'date.after_or_equal' => 'The date must be today or later.',
            'receipt_image.image' => 'The receipt must be an image file.',
            'receipt_image.mimes' => 'The receipt must be a file of type: jpeg, png, jpg, gif.',
            'receipt_image.max' => 'The receipt may not be greater than 2MB.',
        ]);

        $data = [
            'planning_id' => $planning->id,
            'category' => $request->category,
            'description' => $request->description,
            'amount' => $request->amount,
            'budget_type' => $request->budget_type,
            'date' => $request->date,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ];

        // Handle receipt image upload
        if ($request->hasFile('receipt_image')) {
            $imagePath = $request->file('receipt_image')->store('receipts', 'public');
            $data['receipt_image'] = $imagePath;
        }

        $budgetRecord = PlanningBudget::create($data);

        return response()->json([
            'success' => true,
            'budget_record' => $budgetRecord->load('creator'),
            'message' => 'Budget record created successfully.'
        ]);
    }

    /**
     * Update the specified budget record.
     */
    public function update(Request $request, Planning $planning, PlanningBudget $planningBudget)
    {
        // Check access
        if (!$planning->is_public &&
            $planning->created_by !== Auth::id() &&
            !$planning->assignedUsers()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You do not have access to this planning.');
        }

        $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'budget_type' => 'required|in:income,expense',
            'date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'category.required' => 'The category field is required.',
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 0.01.',
            'budget_type.required' => 'The budget type field is required.',
            'budget_type.in' => 'The budget type must be either income or expense.',
            'date.required' => 'The date field is required.',
            'date.after_or_equal' => 'The date must be today or later.',
            'receipt_image.image' => 'The receipt must be an image file.',
            'receipt_image.mimes' => 'The receipt must be a file of type: jpeg, png, jpg, gif.',
            'receipt_image.max' => 'The receipt may not be greater than 2MB.',
        ]);

        $data = [
            'category' => $request->category,
            'description' => $request->description,
            'amount' => $request->amount,
            'budget_type' => $request->budget_type,
            'date' => $request->date,
            'notes' => $request->notes,
        ];

        // Handle receipt image upload
        if ($request->hasFile('receipt_image')) {
            // Delete old image if exists
            if ($planningBudget->receipt_image) {
                Storage::disk('public')->delete($planningBudget->receipt_image);
            }
            $imagePath = $request->file('receipt_image')->store('receipts', 'public');
            $data['receipt_image'] = $imagePath;
        }

        $planningBudget->update($data);

        return response()->json([
            'success' => true,
            'budget_record' => $planningBudget->load('creator'),
            'message' => 'Budget record updated successfully.'
        ]);
    }

    /**
     * Remove the specified budget record.
     */
    public function destroy(Planning $planning, PlanningBudget $planningBudget)
    {
        // Check access
        if (!$planning->is_public &&
            $planning->created_by !== Auth::id() &&
            !$planning->assignedUsers()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You do not have access to this planning.');
        }

        // Delete receipt image if exists
        if ($planningBudget->receipt_image) {
            Storage::disk('public')->delete($planningBudget->receipt_image);
        }

        $planningBudget->delete();

        return response()->json([
            'success' => true,
            'message' => 'Budget record deleted successfully.'
        ]);
    }
}
