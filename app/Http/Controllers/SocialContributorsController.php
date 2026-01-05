<?php

namespace App\Http\Controllers;

use App\Models\Social_Contribution;
use App\Models\Social_Contributors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SocialContributorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $contributionId = $request->query('contribution_id');

        if (Auth::user()->isAdmin()) {
            $query = Social_Contributors::query()->with(['user', 'socialContribution']);
            if ($contributionId) {
                $query->where('social_contribution_id', $contributionId);
            }
            $contributors = $query->latest()->paginate(10)->withQueryString();
        } else {
            $query = Auth::user()->contributions()->with('socialContribution');
            if ($contributionId) {
                $query->where('social_contribution_id', $contributionId);
            }
            $contributors = $query->latest()->paginate(10)->withQueryString();
        }

        // For filter dropdown
        $allContributions = Social_Contribution::orderBy('date', 'desc')->get(['id', 'title']);

        return view('social_contributors.index', compact('contributors', 'allContributions', 'contributionId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'social_contribution_id' => 'required|exists:social_contributions,id',
            'user_id' => Auth::user()->isAdmin() ? 'required|exists:users,id' : '',
            'amount' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'note' => 'nullable|string',
        ]);

        $data = $request->except('image');

        // If not admin, set user_id to current user
        if (!Auth::user()->isAdmin()) {
            $data['user_id'] = Auth::id();
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('contribution_receipts', 'public');
            $data['image'] = $imagePath;
        }

        Social_Contributors::create($data);

        return redirect()->route('social-contributions.show', $request->social_contribution_id)
            ->with('success', 'Contribution recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contributor = Social_Contributors::findOrFail($id);

        // Check if user has permission to delete this contribution
        if (!Auth::user()->isAdmin() && $contributor->user_id !== Auth::id()) {
            return redirect()->route('social-contributors.index')
                ->with('error', 'You do not have permission to delete this contribution.');
        }

        // Delete image if exists
        if ($contributor->image) {
            Storage::disk('public')->delete($contributor->image);
        }

        $contributionId = $contributor->social_contribution_id;
        $contributor->delete();

        return redirect()->route('social-contributions.show', $contributionId)
            ->with('success', 'Contribution deleted successfully.');
    }

    /**
     * Add a user as a contributor to a social contribution.
     */
    public function contribute(Request $request, string $contributionId)
    {
        $contribution = Social_Contribution::findOrFail($contributionId);

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'note' => 'nullable|string',
            'user_id' => Auth::user()->isAdmin() ? 'nullable|exists:users,id' : '',
        ]);

        if (Auth::user()->isAdmin() && !$request->filled('user_id')) {
            return redirect()->back()->withErrors(['user_id' => 'Please select a user to contribute for.'])->withInput();
        }

        $userId = Auth::user()->isAdmin()
            ? (int) $request->input('user_id')
            : Auth::id();

        // Check if user has already contributed to this social contribution
        // Only block duplicate contributions for non-admins; admins may contribute multiple times on behalf of a user
        if (!Auth::user()->isAdmin()) {
            $existingContribution = Social_Contributors::where('social_contribution_id', $contributionId)
                ->where('user_id', $userId)
                ->first();
            if ($existingContribution) {
                return redirect()->back()->with('error', 'You have already contributed to this social contribution.');
            }
        }

        $data = [
            'social_contribution_id' => $contributionId,
            'user_id' => $userId,
            'amount' => $request->amount,
            'note' => $request->note,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('contribution_receipts', 'public');
            $data['image'] = $imagePath;
        }

        // Enforce fixed vs open amount rule
        $minRequired = $contribution->type === 'fixed' ? max($contribution->single_amount ?? 0, 0) : 0;
        if ($contribution->type === 'fixed' && $request->amount < $minRequired) {
            return redirect()->back()->withErrors(['amount' => 'Amount must be at least '.number_format($minRequired, 2).' for fixed contributions.'])->withInput();
        }

        Social_Contributors::create($data);

        return redirect()->route('social-contributions.show', $contributionId)
            ->with('success', 'You have successfully contributed to this social contribution.');
    }
}
