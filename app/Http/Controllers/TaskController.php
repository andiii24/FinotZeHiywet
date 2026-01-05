<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Group_cat;
use App\Models\UserTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $query = Task::query();
        } else {
            // Get tasks assigned directly to the user, to their group, or to all users
            $query = Task::where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere(function($q) use ($user) {
                      $q->where('group_cat_id', $user->group_cat_id)
                        ->whereNotNull('group_cat_id');
                  })
                  ->orWhere('for_all', true);
            });
        }

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->withStatus($request->status);
        }

        if ($request->has('priority') && $request->priority) {
            $query->withPriority($request->priority);
        }

        $tasks = $query->with(['user', 'groupCat'])->latest()->paginate(10);

        // For global tasks, get the user's individual completion status
        $userTaskIds = [];
        foreach ($tasks as $task) {
            if ($task->for_all) {
                $userTaskIds[] = $task->id;
            }
        }

        if (!empty($userTaskIds)) {
            $userTasks = UserTask::where('user_id', $user->id)
                ->whereIn('task_id', $userTaskIds)
                ->get()
                ->keyBy('task_id');
        } else {
            $userTasks = collect();
        }

        return view('tasks.index', compact('tasks', 'userTasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $groupCategories = Group_cat::all();
        return view('tasks.create', compact('users', 'groupCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:500',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'deadline' => 'nullable|date',
            'description' => 'nullable|string',
            'assignment_type' => 'required|in:user,group,all',
            'user_id' => 'nullable|exists:users,id',
            'group_cat_id' => 'nullable|exists:group_cats,id',
        ]);

        $data = $request->all();
        $data['for_all'] = false;

        // Handle assignment type
        switch ($request->assignment_type) {
            case 'user':
                // If not admin or user_id not provided, set to current user
                if (!Auth::user()->isAdmin() || !isset($data['user_id'])) {
                    $data['user_id'] = Auth::id();
                }
                $data['group_cat_id'] = null;
                break;

            case 'group':
                // Assign to a group
                $data['user_id'] = null;
                break;

            case 'all':
                // Assign to all users
                $data['user_id'] = null;
                $data['group_cat_id'] = null;
                $data['for_all'] = true;
                break;
        }

        $task = Task::create($data);

        // If this is a global task, create user task records for all users
        if ($task->for_all) {
            $users = User::all();
            foreach ($users as $u) {
                UserTask::create([
                    'user_id' => $u->id,
                    'task_id' => $task->id,
                    'status' => 'pending'
                ]);
            }
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::with(['user', 'groupCat'])->findOrFail($id);
        $user = Auth::user();

        // Check if user has permission to view this task
        if (!$user->isAdmin() &&
            $task->user_id !== $user->id &&
            $task->group_cat_id !== $user->group_cat_id &&
            !$task->for_all) {
            return redirect()->route('tasks.index')
                ->with('error', 'You do not have permission to view this task.');
        }

        // For global tasks, get the user's individual completion status
        $userTask = null;
        if ($task->for_all && !$user->isAdmin()) {
            $userTask = UserTask::where('user_id', $user->id)
                ->where('task_id', $task->id)
                ->first();
        }

        return view('tasks.show', compact('task', 'userTask'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = Task::findOrFail($id);
        $user = Auth::user();

        // Check if user has permission to edit this task
        if (!$user->isAdmin() &&
            $task->user_id !== $user->id &&
            $task->group_cat_id !== $user->group_cat_id &&
            !$task->for_all) {
            return redirect()->route('tasks.index')
                ->with('error', 'You do not have permission to edit this task.');
        }

        // For group or all tasks, only admin can edit unless it's just to mark as completed
        if (!$user->isAdmin() && ($task->group_cat_id || $task->for_all)) {
            return view('tasks.edit', compact('task'));
        }

        $users = User::all();
        $groupCategories = Group_cat::all();

        return view('tasks.edit', compact('task', 'users', 'groupCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail($id);
        $user = Auth::user();

        // Check if user has permission to update this task
        if (!$user->isAdmin() &&
            $task->user_id !== $user->id &&
            $task->group_cat_id !== $user->group_cat_id &&
            !$task->for_all) {
            return redirect()->route('tasks.index')
                ->with('error', 'You do not have permission to update this task.');
        }

        // For global tasks, non-admin users can mark as completed only for themselves
        if ($task->for_all && !$user->isAdmin()) {
            $request->validate([
                'status' => 'required|in:pending,in_progress,completed,cancelled',
            ]);

            // Find or create user task record
            $userTask = UserTask::firstOrNew([
                'user_id' => $user->id,
                'task_id' => $task->id
            ]);

            $userTask->status = $request->status;
            $userTask->save();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your task status updated successfully']);
            }

            return redirect()->route('tasks.show', $task->id)
                ->with('success', 'Your task status updated successfully.');
        }

        // Regular user can only update status for group tasks
        if (!$user->isAdmin() && $task->group_cat_id) {
            $request->validate([
                'status' => 'required|in:pending,in_progress,completed,cancelled',
            ]);

            $task->update(['status' => $request->status]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Task status updated successfully']);
            }

            return redirect()->route('tasks.show', $task->id)
                ->with('success', 'Task status updated successfully.');
        }

        // Admin or task owner can update all fields
        $request->validate([
            'name' => 'required|string|max:500',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'deadline' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();

        // If admin is updating assignment type
        if ($user->isAdmin() && isset($data['assignment_type'])) {
            switch ($data['assignment_type']) {
                case 'user':
                    $data['group_cat_id'] = null;
                    $data['for_all'] = false;
                    break;

                case 'group':
                    $data['user_id'] = null;
                    $data['for_all'] = false;
                    break;

                case 'all':
                    $data['user_id'] = null;
                    $data['group_cat_id'] = null;
                    $data['for_all'] = true;
                    break;
            }
        } elseif (!$user->isAdmin()) {
            // Non-admin users can't change assignment
            unset($data['user_id']);
            unset($data['group_cat_id']);
            unset($data['for_all']);
        }

        $task->update($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Task updated successfully']);
        }

        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $user = Auth::user();

        // Only admin can delete group or global tasks
        if (!$user->isAdmin() && ($task->group_cat_id || $task->for_all)) {
            return redirect()->route('tasks.index')
                ->with('error', 'Only administrators can delete group or global tasks.');
        }

        // Check if user has permission to delete this task
        if (!$user->isAdmin() && $task->user_id !== $user->id) {
            return redirect()->route('tasks.index')
                ->with('error', 'You do not have permission to delete this task.');
        }

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
