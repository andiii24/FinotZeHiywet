<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group_cat;
use Illuminate\Http\Request;

class GroupCatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groupCats = Group_cat::withCount('users')->get();
        return view('admin.group_cats.index', compact('groupCats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.group_cats.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:500|unique:group_cats,name',
        ]);

        Group_cat::create($request->all());

        return redirect()->route('admin.group_cats.index')
            ->with('success', 'Group category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $groupCat = Group_cat::with('users')->findOrFail($id);
        return view('admin.group_cats.show', compact('groupCat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $groupCat = Group_cat::findOrFail($id);
        return view('admin.group_cats.edit', compact('groupCat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $groupCat = Group_cat::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:500|unique:group_cats,name,' . $id,
        ]);

        $groupCat->update($request->all());

        return redirect()->route('admin.group_cats.index')
            ->with('success', 'Group category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $groupCat = Group_cat::findOrFail($id);

        // Check if there are users in this group
        if ($groupCat->users()->count() > 0) {
            return redirect()->route('admin.group_cats.index')
                ->with('error', 'Cannot delete group category with associated users');
        }

        $groupCat->delete();

        return redirect()->route('admin.group_cats.index')
            ->with('success', 'Group category deleted successfully');
    }
}
