<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Job_Category;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function __construct()
    {
        // Only admins can create/update/delete
        $this->middleware('admin')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = Job::with(['category', 'creator'])->latest()->paginate(10);
        return view('jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500',
            'description' => 'nullable|string',
            'vacancy' => 'required|integer|min:1',
            'status' => 'required|in:draft,open,closed',
            'job_category_id' => 'required|exists:job_categories,id',
        ]);

        $validated['created_by'] = Auth::id();

        $job = Job::create($validated);

        return redirect()->route('jobs.show', $job->id)->with('success', 'Job created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = Job::with(['category', 'creator'])->findOrFail($id);
        return view('jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $job = Job::findOrFail($id);
        return view('jobs.edit', compact('job'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500',
            'description' => 'nullable|string',
            'vacancy' => 'required|integer|min:1',
            'status' => 'required|in:draft,open,closed',
            'job_category_id' => 'required|exists:job_categories,id',
        ]);

        $job = Job::findOrFail($id);
        $job->update($validated);

        return redirect()->route('jobs.show', $job->id)->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $job = Job::findOrFail($id);
        $job->delete();
        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully.');
    }
}
