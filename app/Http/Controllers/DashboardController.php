<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Group_cat;
use App\Models\Job;
use App\Models\Monthly_Payment;
use App\Models\Social_Contribution;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Get user counts
        $userCount = User::count();

        // Get payment stats
        $paymentsCount = Monthly_Payment::count();
        $pendingPayments = Monthly_Payment::where('status', 'pending')->count();
        $approvedPayments = Monthly_Payment::where('status', 'approved')->count();
        $rejectedPayments = Monthly_Payment::where('status', 'rejected')->count();

        // Get contribution stats
        $contributionsCount = Social_Contribution::count();

        // Get job stats
        $openJobsCount = Job::where('status', 'open')->count();

        // Get upcoming events
        $upcomingEvents = Event::where('date', '>=', now())
            ->orderBy('date')
            ->limit(5)
            ->get();

        // Get latest tasks for the current user (most recent first)
        $myTasks = Task::where('user_id', Auth::id())
            ->where('status', '!=', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get group distribution (for admin)
        $groups = Group_cat::withCount('users')
            ->orderBy('users_count', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'userCount',
            'paymentsCount',
            'pendingPayments',
            'approvedPayments',
            'rejectedPayments',
            'contributionsCount',
            'openJobsCount',
            'upcomingEvents',
            'myTasks',
            'groups'
        ));
    }
}
