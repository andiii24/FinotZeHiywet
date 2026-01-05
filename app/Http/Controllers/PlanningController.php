<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use App\Models\PlanningTask;
use App\Models\PlanningReminder;
use App\Models\PlanningBudget;
use App\Models\Group_cat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PlanningController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Planning::with(['groupCat', 'creator', 'planningTasks'])
            ->where(function($q) {
                $q->where('is_public', true)
                  ->orWhere('created_by', Auth::id())
                  ->orWhereHas('assignedUsers', function($userQuery) {
                      $userQuery->where('user_id', Auth::id());
                  });
            });

        // Filter by timeframe type
        if ($request->filled('timeframe_type')) {
            $query->where('timeframe_type', $request->timeframe_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority_level')) {
            $query->where('priority_level', $request->priority_level);
        }

        // Filter by group category
        if ($request->filled('group_cat_id')) {
            $query->where('group_cat_id', $request->group_cat_id);
        }

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('objectives', 'like', "%{$search}%");
            });
        }

        $plannings = $query->orderBy('created_at', 'desc')->paginate(15);
        $groupCats = Group_cat::all();
        $timeframeTypes = Planning::getTimeframeTypes();
        $statuses = Planning::getStatuses();
        $priorityLevels = Planning::getPriorityLevels();

        return view('plannings.index', compact(
            'plannings',
            'groupCats',
            'timeframeTypes',
            'statuses',
            'priorityLevels'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groupCats = Group_cat::all();
        $timeframeTypes = Planning::getTimeframeTypes();
        $priorityLevels = Planning::getPriorityLevels();
        $statuses = Planning::getStatuses();

        return view('plannings.create', compact(
            'groupCats',
            'timeframeTypes',
            'priorityLevels',
            'statuses'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'objectives' => 'nullable|string',
            'timeframe_type' => 'required|in:yearly,quarterly,monthly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'priority_level' => 'required|in:low,medium,high,critical',
            'group_cat_id' => 'required|exists:group_cats,id',
            'group_list' => 'nullable|array',
            'group_list.*' => 'exists:group_cats,id',
            'budget_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:planning,active,completed,cancelled',
            'is_public' => 'boolean',
        ]);

        $planning = Planning::create([
            'title' => $request->title,
            'description' => $request->description,
            'objectives' => $request->objectives,
            'timeframe_type' => $request->timeframe_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'priority_level' => $request->priority_level,
            'group_cat_id' => $request->group_cat_id,
            'group_list' => $request->group_list ?? [],
            'budget_amount' => $request->budget_amount ?? 0,
            'status' => $request->status,
            'created_by' => Auth::id(),
            'is_public' => $request->has('is_public'),
        ]);

        return redirect()->route('plannings.show', $planning)
            ->with('success', 'Planning created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Planning $planning)
    {
        // Check if user has access to this planning
        if (!$planning->is_public &&
            $planning->created_by !== Auth::id() &&
            !$planning->assignedUsers()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You do not have access to this planning.');
        }

        $planning->load([
            'groupCat',
            'creator',
            'planningTasks.assignedUser',
            'reminders',
            'budgetRecords',
            'assignedUsers'
        ]);

        // Get statistics
        $stats = [
            'total_tasks' => $planning->planningTasks()->count(),
            'completed_tasks' => $planning->planningTasks()->where('status', 'completed')->count(),
            'overdue_tasks' => $planning->planningTasks()->where('end_date', '<', Carbon::now())
                ->where('status', '!=', 'completed')->count(),
            'total_budget_spent' => $planning->budgetRecords()->expense()->sum('amount'),
            'total_budget_income' => $planning->budgetRecords()->income()->sum('amount'),
            'total_expense' => $planning->budgetRecords()->expense()->sum('amount'),
            'total_income' => $planning->budgetRecords()->income()->sum('amount'),
        ];

        return view('plannings.show', compact('planning', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Planning $planning)
    {
        // Only creator or admin can edit
        if ($planning->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'You do not have permission to edit this planning.');
        }

        $groupCats = Group_cat::all();
        $timeframeTypes = Planning::getTimeframeTypes();
        $priorityLevels = Planning::getPriorityLevels();
        $statuses = Planning::getStatuses();

        return view('plannings.edit', compact(
            'planning',
            'groupCats',
            'timeframeTypes',
            'priorityLevels',
            'statuses'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Planning $planning)
    {
        // Only creator or admin can update
        if ($planning->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'You do not have permission to update this planning.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'objectives' => 'nullable|string',
            'timeframe_type' => 'required|in:yearly,quarterly,monthly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'priority_level' => 'required|in:low,medium,high,critical',
            'group_cat_id' => 'required|exists:group_cats,id',
            'group_list' => 'nullable|array',
            'group_list.*' => 'exists:group_cats,id',
            'budget_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:planning,active,completed,cancelled',
            'is_public' => 'boolean',
        ]);

        $planning->update([
            'title' => $request->title,
            'description' => $request->description,
            'objectives' => $request->objectives,
            'timeframe_type' => $request->timeframe_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'priority_level' => $request->priority_level,
            'group_cat_id' => $request->group_cat_id,
            'group_list' => $request->group_list ?? [],
            'budget_amount' => $request->budget_amount ?? 0,
            'status' => $request->status,
            'is_public' => $request->has('is_public'),
        ]);

        return redirect()->route('plannings.show', $planning)
            ->with('success', 'Planning updated successfully.');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Planning $planning)
    {
        // Only creator or admin can delete
        if ($planning->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'You do not have permission to delete this planning.');
        }

        // Soft delete the planning
        $planning->delete();

        return redirect()->route('plannings.index')
            ->with('success', 'Planning deleted successfully.');
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function forceDelete(Planning $planning)
    {
        // Only creator or admin can permanently delete
        if ($planning->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'You do not have permission to permanently delete this planning.');
        }

        // Permanently delete the planning and all related data
        $planning->forceDelete();

        return redirect()->route('plannings.index')
            ->with('success', 'Planning permanently deleted.');
    }

    /**
     * Restore a soft-deleted planning.
     */
    public function restore(Planning $planning)
    {
        // Only creator or admin can restore
        if ($planning->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'You do not have permission to restore this planning.');
        }

        // Restore the planning
        $planning->restore();

        return redirect()->route('plannings.show', $planning)
            ->with('success', 'Planning restored successfully.');
    }

    /**
     * Show trashed plannings (soft deleted).
     */
    public function trashed()
    {
        $plannings = Planning::onlyTrashed()
            ->where(function($q) {
                $q->where('created_by', Auth::id())
                  ->orWhere('is_public', true);
            })
            ->with(['groupCat', 'creator'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('plannings.trashed', compact('plannings'));
    }

    /**
     * Show the planning dashboard with charts and statistics.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Base query for plannings accessible to the user
        $baseQuery = function() use ($user) {
            return Planning::where(function($q) use ($user) {
                $q->where('is_public', true)
                  ->orWhere('created_by', $user->id)
                  ->orWhereHas('assignedUsers', function($userQuery) use ($user) {
                      $userQuery->where('user_id', $user->id);
                  });
            });
        };

        // Overall statistics
        $stats = [
            'total_plannings' => $baseQuery()->count(),
            'active_plannings' => $baseQuery()->where('status', 'active')->count(),
            'completed_plannings' => $baseQuery()->where('status', 'completed')->count(),
            'overdue_plannings' => $baseQuery()->where('end_date', '<', Carbon::now())
                ->where('status', '!=', 'completed')->count(),
        ];

        // Recent plannings
        $recentPlannings = $baseQuery()->with(['groupCat', 'creator'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Upcoming deadlines
        $upcomingDeadlines = $baseQuery()->where('end_date', '>=', Carbon::now())
            ->where('end_date', '<=', Carbon::now()->addDays(30))
            ->where('status', '!=', 'completed')
            ->orderBy('end_date', 'asc')
            ->limit(10)
            ->get();

        // Priority distribution
        $priorityDistribution = $baseQuery()->select('priority_level', DB::raw('count(*) as count'))
            ->groupBy('priority_level')
            ->pluck('count', 'priority_level');

        // Status distribution
        $statusDistribution = $baseQuery()->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('plannings.dashboard', compact(
            'stats',
            'recentPlannings',
            'upcomingDeadlines',
            'priorityDistribution',
            'statusDistribution'
        ));
    }

    /**
     * Show Gantt chart view for a planning.
     */
    public function gantt(Planning $planning)
    {
        // Check access
        if (!$planning->is_public &&
            $planning->created_by !== Auth::id() &&
            !$planning->assignedUsers()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You do not have access to this planning.');
        }

        $planning->load(['planningTasks.assignedUser', 'planningTasks.dependencyTasks']);

        return view('plannings.gantt', compact('planning'));
    }

    /**
     * Show calendar view for plannings.
     */
    public function calendar(Request $request)
    {
        $view = $request->get('view', 'month'); // month, week, day

        $planningsQuery = Planning::where(function($q) {
            $q->where('is_public', true)
              ->orWhere('created_by', Auth::id())
              ->orWhereHas('assignedUsers', function($userQuery) {
                  $userQuery->where('user_id', Auth::id());
              });
        });

        // Filter by date range based on view
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        if ($view === 'week') {
            $startDate = Carbon::parse($startDate)->startOfWeek();
            $endDate = Carbon::parse($startDate)->endOfWeek();
        } elseif ($view === 'day') {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($startDate)->endOfDay();
        }

        $plannings = $planningsQuery->whereBetween('start_date', [$startDate, $endDate])
            ->orWhereBetween('end_date', [$startDate, $endDate])
            ->orWhere(function($q) use ($startDate, $endDate) {
                $q->where('start_date', '<=', $startDate)
                  ->where('end_date', '>=', $endDate);
            })
            ->with(['groupCat', 'creator'])
            ->get();

        return view('plannings.calendar', compact('plannings', 'view', 'startDate', 'endDate'));
    }
}
