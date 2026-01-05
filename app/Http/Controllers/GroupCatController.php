<?php

namespace App\Http\Controllers;

use App\Models\Group_cat;
use Illuminate\Http\Request;

class GroupCatController extends Controller
{
    // No constructor needed, we'll handle auth checks in each method
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $group_cats = Group_cat::all();
        return view('group_cats.index', compact('group_cats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('group_cats.index')
                ->with('error', 'You do not have permission to create group categories.');
        }

        return view('group_cats.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('group_cats.index')
                ->with('error', 'You do not have permission to create group categories.');
        }

        $request->validate([
            'name' => 'required|string|max:500',
        ]);

        Group_cat::create($request->all());

        return redirect()->route('group_cats.index')
            ->with('success', 'Group category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $group_cat = Group_cat::findOrFail($id);
        return view('group_cats.show', compact('group_cat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('group_cats.index')
                ->with('error', 'You do not have permission to edit group categories.');
        }

        $group_cat = Group_cat::findOrFail($id);
        return view('group_cats.edit', compact('group_cat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('group_cats.index')
                ->with('error', 'You do not have permission to update group categories.');
        }

        $request->validate([
            'name' => 'required|string|max:500',
        ]);

        $group_cat = Group_cat::findOrFail($id);
        $group_cat->update($request->all());

        return redirect()->route('group_cats.index')
            ->with('success', 'Group category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('group_cats.index')
                ->with('error', 'You do not have permission to delete group categories.');
        }

        $group_cat = Group_cat::findOrFail($id);
        $group_cat->delete();

        return redirect()->route('group_cats.index')
            ->with('success', 'Group category deleted successfully.');
    }
}
