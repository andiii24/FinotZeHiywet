<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Events_Category;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::with('category');

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('event_category_id', $request->category);
        }

        // Filter by date
        if ($request->has('date')) {
            switch ($request->date) {
                case 'upcoming':
                    $query->where('date', '>=', now());
                    break;
                case 'past':
                    $query->where('date', '<', now());
                    break;
                case 'today':
                    $query->whereDate('date', now()->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('date', now()->month)
                          ->whereYear('date', now()->year);
                    break;
            }
        }

        // Default sort by date (upcoming first)
        $query->orderBy('date', 'asc');

        $events = $query->paginate(9);
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Events_Category::all();
        return view('events.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500',
            'event_category_id' => 'required|exists:event_categories,id',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:500',
            'created_by' => 'required|exists:users,id',
        ]);

        $event = Event::create($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::with(['category', 'creator'])->findOrFail($id);
        return view('events.show', compact('event'));
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
