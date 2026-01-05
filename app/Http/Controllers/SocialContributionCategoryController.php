<?php

namespace App\Http\Controllers;

use App\Models\Social_Contribution_Category;
use Illuminate\Http\Request;

class SocialContributionCategoryController extends Controller
{
    public function __construct()
    {
        // Only admins manage categories
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Social_Contribution_Category::latest()->paginate(10);
        return view('social_contribution_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('social_contribution_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500|unique:social_contribution_categories,name',
        ]);

        Social_Contribution_Category::create($validated);

        return redirect()->route('social-contribution-categories.index')
            ->with('success', 'Category created successfully.');
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
        //
    }
}
