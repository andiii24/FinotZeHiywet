<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanningTeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display team members for a planning.
     */
    public function index(Planning $planning)
    {
        // Check access
        if (!$planning->is_public &&
            $planning->created_by !== Auth::id() &&
            !$planning->assignedUsers()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You do not have access to this planning.');
        }

        $teamMembers = $planning->assignedUsers()->withPivot('role', 'assigned_at')->get();
        $availableUsers = User::whereNotIn('id', $teamMembers->pluck('id'))->get();

        return response()->json([
            'team_members' => $teamMembers,
            'available_users' => $availableUsers
        ]);
    }

    /**
     * Add a team member to the planning.
     */
    public function store(Request $request, Planning $planning)
    {
        // Check access - only creator or admin can add team members
        if ($planning->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'You do not have permission to add team members.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:viewer,contributor,manager',
        ]);

        // Check if user is already assigned
        if ($planning->assignedUsers()->where('user_id', $request->user_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User is already assigned to this planning.'
            ], 400);
        }

        $planning->assignedUsers()->attach($request->user_id, [
            'role' => $request->role,
            'assigned_at' => now(),
        ]);

        $user = User::find($request->user_id);

        return response()->json([
            'success' => true,
            'user' => $user,
            'role' => $request->role,
            'message' => 'Team member added successfully.'
        ]);
    }

    /**
     * Update team member role.
     */
    public function update(Request $request, Planning $planning, User $user)
    {
        // Check access - only creator or admin can update team members
        if ($planning->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'You do not have permission to update team members.');
        }

        $request->validate([
            'role' => 'required|in:viewer,contributor,manager',
        ]);

        $planning->assignedUsers()->updateExistingPivot($user->id, [
            'role' => $request->role,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Team member role updated successfully.'
        ]);
    }

    /**
     * Remove team member from planning.
     */
    public function destroy(Planning $planning, User $user)
    {
        // Check access - only creator or admin can remove team members
        if ($planning->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'You do not have permission to remove team members.');
        }

        $planning->assignedUsers()->detach($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Team member removed successfully.'
        ]);
    }
}
