<?php

namespace App\Http\Controllers;

use App\Models\SpiritualResourceCategory;
use Illuminate\Http\Request;

class SpiritualResourceCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $categories = SpiritualResourceCategory::withCount('resources')
            ->orderBy('name')
            ->paginate(15);

        return view('spiritual_resource_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('spiritual_resource_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500|unique:spiritual_resource_categories,name',
            'description' => 'nullable|string',
        ]);

        SpiritualResourceCategory::create($validated);

        return redirect()->route('spiritual-resource-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(string $id)
    {
        $category = SpiritualResourceCategory::findOrFail($id);

        return view('spiritual_resource_categories.edit', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $category = SpiritualResourceCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:500|unique:spiritual_resource_categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('spiritual-resource-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(string $id)
    {
        $category = SpiritualResourceCategory::findOrFail($id);

        // Detach pivot only — resources remain in the library
        $category->resources()->detach();
        $category->delete();

        return redirect()->route('spiritual-resource-categories.index')
            ->with('success', 'Category removed. Associated resources were kept.');
    }
}
