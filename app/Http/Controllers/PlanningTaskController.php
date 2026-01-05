<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use App\Models\PlanningTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PlanningTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of tasks for a planning.
     */
    public function index(Planning $planning)
    {
        // Check access
        if (!$planning->is_public &&
            $planning->created_by !== Auth::id() &&
            !$planning->assignedUsers()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You do not have access to this planning.');
        }

        $tasks = $planning->planningTasks()->with(['assignedUser', 'creator'])->get();
        $users = User::all();

        return response()->json([
            'tasks' => $tasks,
            'users' => $users
        ]);
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request, Planning $planning)
    {
        // Check access
        if (!$planning->is_public &&
            $planning->created_by !== Auth::id() &&
            !$planning->assignedUsers()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You do not have access to this planning.');
        }

        // Debug: Log the received data
        \Log::info('Task creation request data:', $request->all());

        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
                'priority_level' => 'required|in:low,medium,high,critical',
                'assigned_to' => 'nullable|exists:users,id',
                'estimated_hours' => 'nullable|numeric|min:0',
                'dependencies' => 'nullable|array',
                'dependencies.*' => 'exists:planning_tasks,id',
            ], [
                'title.required' => 'The title field is required.',
                'start_date.required' => 'The start date field is required.',
                'start_date.after_or_equal' => 'The start date must be today or later.',
                'end_date.required' => 'The end date field is required.',
                'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
                'priority_level.required' => 'The priority level field is required.',
                'priority_level.in' => 'The priority level must be one of: low, medium, high, critical.',
                'assigned_to.exists' => 'The selected user does not exist.',
                'estimated_hours.numeric' => 'The estimated hours must be a number.',
                'estimated_hours.min' => 'The estimated hours must be at least 0.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Task validation failed:', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            throw $e;
        }

        // Convert empty string to null for assigned_to
        $assignedTo = $request->assigned_to;
        if ($assignedTo === '') {
            $assignedTo = null;
        }

        $task = $planning->planningTasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'priority_level' => $request->priority_level,
            'assigned_to' => $assignedTo,
            'estimated_hours' => $request->estimated_hours,
            'dependencies' => $request->dependencies ?? [],
            'created_by' => Auth::id(),
        ]);

        // Update planning progress
        $this->updatePlanningProgress($planning);

        return response()->json([
            'success' => true,
            'task' => $task->load(['assignedUser', 'creator']),
            'message' => 'Task created successfully.'
        ]);
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, Planning $planning, PlanningTask $planningTask)
    {
        // Check access
        if (!$planning->is_public &&
            $planning->created_by !== Auth::id() &&
            !$planning->assignedUsers()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You do not have access to this planning.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'priority_level' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:not_started,in_progress,completed,on_hold,cancelled',
            'assigned_to' => 'nullable|exists:users,id',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
            'progress_percentage' => 'nullable|numeric|min:0|max:100',
            'dependencies' => 'nullable|array',
            'dependencies.*' => 'exists:planning_tasks,id',
        ], [
            'title.required' => 'The title field is required.',
            'start_date.required' => 'The start date field is required.',
            'start_date.after_or_equal' => 'The start date must be today or later.',
            'end_date.required' => 'The end date field is required.',
            'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
            'priority_level.required' => 'The priority level field is required.',
            'priority_level.in' => 'The priority level must be one of: low, medium, high, critical.',
            'assigned_to.exists' => 'The selected user does not exist.',
            'estimated_hours.numeric' => 'The estimated hours must be a number.',
            'estimated_hours.min' => 'The estimated hours must be at least 0.',
        ]);

        $planningTask->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'priority_level' => $request->priority_level,
            'status' => $request->status,
            'assigned_to' => $request->assigned_to,
            'estimated_hours' => $request->estimated_hours,
            'actual_hours' => $request->actual_hours,
            'progress_percentage' => $request->progress_percentage ?? 0,
            'dependencies' => $request->dependencies ?? [],
        ]);

        // Update planning progress
        $this->updatePlanningProgress($planning);

        return response()->json([
            'success' => true,
            'task' => $planningTask->load(['assignedUser', 'creator']),
            'message' => 'Task updated successfully.'
        ]);
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Planning $planning, PlanningTask $planningTask)
    {
        // Check access
        if (!$planning->is_public &&
            $planning->created_by !== Auth::id() &&
            !$planning->assignedUsers()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You do not have access to this planning.');
        }

        $planningTask->delete();

        // Update planning progress
        $this->updatePlanningProgress($planning);

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.'
        ]);
    }

    /**
     * Update planning progress based on task completion.
     */
    private function updatePlanningProgress(Planning $planning)
    {
        $totalTasks = $planning->planningTasks()->count();
        if ($totalTasks > 0) {
            $completedTasks = $planning->planningTasks()->where('status', 'completed')->count();
            $progressPercentage = ($completedTasks / $totalTasks) * 100;

            $planning->update(['progress_percentage' => round($progressPercentage, 2)]);
        }
    }
}
