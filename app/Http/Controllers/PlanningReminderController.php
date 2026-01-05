<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use App\Models\PlanningReminder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PlanningReminderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of reminders for a planning.
     */
    public function index(Planning $planning)
    {
        // Check access
        if (!$planning->is_public &&
            $planning->created_by !== Auth::id() &&
            !$planning->assignedUsers()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You do not have access to this planning.');
        }

        $reminders = $planning->reminders()->with(['creator', 'planningTask'])->orderBy('reminder_time', 'asc')->get();
        $users = User::all();

        return response()->json([
            'reminders' => $reminders,
            'users' => $users
        ]);
    }

    /**
     * Store a newly created reminder.
     */
    public function store(Request $request, Planning $planning)
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
            'reminder_date' => 'required|date|after_or_equal:today',
            'reminder_time' => 'required|date_format:H:i',
            'reminder_type' => 'required|in:email,sms,push,in_app',
            'planning_task_id' => 'nullable|exists:planning_tasks,id',
            'recipients' => 'nullable|array',
            'recipients.*' => 'exists:users,id',
        ], [
            'title.required' => 'The title field is required.',
            'reminder_date.required' => 'The reminder date field is required.',
            'reminder_date.after_or_equal' => 'The reminder date must be today or later.',
            'reminder_time.required' => 'The reminder time field is required.',
            'reminder_time.date_format' => 'The reminder time must be in HH:MM format.',
            'reminder_type.required' => 'The reminder type field is required.',
            'reminder_type.in' => 'The reminder type must be one of: email, sms, push, in_app.',
            'planning_task_id.exists' => 'The selected task does not exist.',
            'recipients.array' => 'The recipients must be an array.',
            'recipients.*.exists' => 'One or more selected recipients do not exist.',
        ]);

        // Combine date and time
        $reminderDateTime = Carbon::parse($request->reminder_date . ' ' . $request->reminder_time);

        $reminder = $planning->reminders()->create([
            'title' => $request->title,
            'description' => $request->description,
            'reminder_date' => $request->reminder_date,
            'reminder_time' => $reminderDateTime,
            'reminder_type' => $request->reminder_type,
            'planning_task_id' => $request->planning_task_id,
            'recipients' => $request->recipients ?? [$planning->created_by],
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'reminder' => $reminder->load(['creator', 'planningTask']),
            'message' => 'Reminder created successfully.'
        ]);
    }

    /**
     * Update the specified reminder.
     */
    public function update(Request $request, Planning $planning, PlanningReminder $planningReminder)
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
            'reminder_date' => 'required|date|after_or_equal:today',
            'reminder_time' => 'required|date_format:H:i',
            'reminder_type' => 'required|in:email,sms,push,in_app',
            'planning_task_id' => 'nullable|exists:planning_tasks,id',
            'recipients' => 'nullable|array',
            'recipients.*' => 'exists:users,id',
        ], [
            'title.required' => 'The title field is required.',
            'reminder_date.required' => 'The reminder date field is required.',
            'reminder_date.after_or_equal' => 'The reminder date must be today or later.',
            'reminder_time.required' => 'The reminder time field is required.',
            'reminder_time.date_format' => 'The reminder time must be in HH:MM format.',
            'reminder_type.required' => 'The reminder type field is required.',
            'reminder_type.in' => 'The reminder type must be one of: email, sms, push, in_app.',
            'planning_task_id.exists' => 'The selected task does not exist.',
            'recipients.array' => 'The recipients must be an array.',
            'recipients.*.exists' => 'One or more selected recipients do not exist.',
        ]);

        // Combine date and time
        $reminderDateTime = Carbon::parse($request->reminder_date . ' ' . $request->reminder_time);

        $planningReminder->update([
            'title' => $request->title,
            'description' => $request->description,
            'reminder_date' => $request->reminder_date,
            'reminder_time' => $reminderDateTime,
            'reminder_type' => $request->reminder_type,
            'planning_task_id' => $request->planning_task_id,
            'recipients' => $request->recipients ?? [$planning->created_by],
        ]);

        return response()->json([
            'success' => true,
            'reminder' => $planningReminder->load(['creator', 'planningTask']),
            'message' => 'Reminder updated successfully.'
        ]);
    }

    /**
     * Remove the specified reminder.
     */
    public function destroy(Planning $planning, PlanningReminder $planningReminder)
    {
        // Check access
        if (!$planning->is_public &&
            $planning->created_by !== Auth::id() &&
            !$planning->assignedUsers()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You do not have access to this planning.');
        }

        $planningReminder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reminder deleted successfully.'
        ]);
    }

    /**
     * Mark reminder as sent.
     */
    public function markSent(Planning $planning, PlanningReminder $planningReminder)
    {
        // Check access
        if (!$planning->is_public &&
            $planning->created_by !== Auth::id() &&
            !$planning->assignedUsers()->where('user_id', Auth::id())->exists()) {
            abort(403, 'You do not have access to this planning.');
        }

        $planningReminder->markAsSent();

        return response()->json([
            'success' => true,
            'message' => 'Reminder marked as sent.'
        ]);
    }
}
