<?php

namespace App\Http\Controllers;

use App\Models\Social_Contribution;
use App\Models\Social_Contribution_Category;
use Illuminate\Http\Request;

class SocialContributionController extends Controller
{
    public function __construct()
    {
        // Only admins can create/update/delete social contributions.
        // Listing and viewing are open to any authenticated user.
        $this->middleware('admin')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Social_Contribution::query()
            ->with('category')
            ->withSum('contributors as collected_amount', 'amount');

        // For non-admins, also eager-load only their own contribution row for display
        if (auth()->check() && !auth()->user()->isAdmin()) {
            $userId = auth()->id();
            $query->with(['contributors' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }]);
        }

        $contributions = $query->latest('date')->paginate(10);

        return view('social_contributions.index', compact('contributions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Social_Contribution_Category::all();
        return view('social_contributions.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'social_contribution_category_id' => 'required|exists:social_contribution_categories,id',
            'title' => 'required|string|max:500',
            'description' => 'nullable|string',
            'type' => 'required|in:open,fixed',
            'target_amount' => 'required|numeric|min:0',
            'single_amount' => 'nullable|numeric|min:0|lt:target_amount',
            'date' => 'required|date',
            'location' => 'nullable|string|max:500',
        ]);

        $contribution = Social_Contribution::create($validated);

        return redirect()->route('social-contributions.show', $contribution)
            ->with('success', 'Social contribution created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $isAdmin = auth()->user()->isAdmin();

        $contribution = Social_Contribution::with(['category'])
            ->withSum('contributors as collected_amount', 'amount')
            ->findOrFail($id);

        if ($isAdmin) {
            // Admins can see all contributors
            $contribution->load(['contributors' => function ($q) {
                $q->with('user');
            }]);
        } else {
            // Non-admins: allow viewing so they can contribute, but only load their own row
            $contribution->load(['contributors' => function ($q) {
                $q->where('user_id', auth()->id())->with('user');
            }]);
        }

        return view('social_contributions.show', compact('contribution'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contribution = Social_Contribution::findOrFail($id);
        $categories = Social_Contribution_Category::all();
        return view('social_contributions.edit', compact('contribution', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'social_contribution_category_id' => 'required|exists:social_contribution_categories,id',
            'title' => 'required|string|max:500',
            'description' => 'nullable|string',
            'type' => 'required|in:open,fixed',
            'target_amount' => 'required|numeric|min:0',
            'single_amount' => 'nullable|numeric|min:0|lt:target_amount',
            'date' => 'required|date',
            'location' => 'nullable|string|max:500',
        ]);

        $contribution = Social_Contribution::findOrFail($id);
        $contribution->update($validated);

        return redirect()->route('social-contributions.show', $contribution->id)
            ->with('success', 'Social contribution updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
