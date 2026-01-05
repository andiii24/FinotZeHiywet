<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group_cat;
use App\Models\User;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('groupCat')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groupCats = Group_cat::all();
        $skills = Skill::all();
        return view('admin.users.create', compact('groupCats', 'skills'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:500',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:' . User::ROLE_ADMIN . ',' . User::ROLE_USER,
            'group_cat_id' => 'nullable|exists:group_cats,id',
            'marital_status' => 'nullable|in:single,married',
            'education_background' => 'nullable|string|max:500',
            'work_status' => 'boolean',
            'job_title' => 'nullable|string|max:500',
            'work_place' => 'nullable|string|max:500',
            'skills_array' => 'nullable|string',
        ]);

        $userData = $request->except(['skills_input', 'skills_array']);
        $userData['password'] = Hash::make($request->password);

        // If work_status is not checked, clear job-related fields
        if (!$request->has('work_status') || !$request->work_status) {
            $userData['job_title'] = null;
            $userData['work_place'] = null;
        }

        $user = User::create($userData);

        // Handle skills from JSON array
        if ($request->filled('skills_array')) {
            try {
                $skills = json_decode($request->skills_array, true);

                if (is_array($skills) && count($skills) > 0) {
                    // Create skills that don't exist yet
                    foreach ($skills as $skillName) {
                        $skill = Skill::firstOrCreate(['name' => $skillName]);

                        // Attach skill with default proficiency level
                        $user->skills()->attach($skill->id, ['proficiency_level' => 3]);
                    }
                }
            } catch (\Exception $e) {
                // Log error but continue with user creation
                \Log::error('Error processing skills: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['groupCat', 'skills'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with('skills')->findOrFail($id);
        $groupCats = Group_cat::all();
        $skills = Skill::all();

        return view('admin.users.edit', compact('user', 'groupCats', 'skills'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:500',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:' . User::ROLE_ADMIN . ',' . User::ROLE_USER,
            'group_cat_id' => 'nullable|exists:group_cats,id',
            'marital_status' => 'nullable|in:single,married',
            'education_background' => 'nullable|string|max:500',
            'work_status' => 'boolean',
            'job_title' => 'nullable|string|max:500',
            'work_place' => 'nullable|string|max:500',
            'skills_array' => 'nullable|string',
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8';
        }

        $request->validate($rules);

        $userData = $request->except(['password', 'skills_input', 'skills_array']);

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // If work_status is not checked, clear job-related fields
        if (!$request->has('work_status') || !$request->work_status) {
            $userData['job_title'] = null;
            $userData['work_place'] = null;
        }

        $user->update($userData);

        // Handle skills from JSON array
        if ($request->filled('skills_array')) {
            try {
                $skills = json_decode($request->skills_array, true);

                // First detach all existing skills
                $user->skills()->detach();

                if (is_array($skills) && count($skills) > 0) {
                    // Create skills that don't exist yet and attach them
                    foreach ($skills as $skillName) {
                        $skill = Skill::firstOrCreate(['name' => $skillName]);

                        // Attach skill with default proficiency level
                        $user->skills()->attach($skill->id, ['proficiency_level' => 3]);
                    }
                }
            } catch (\Exception $e) {
                // Log error but continue with user update
                \Log::error('Error processing skills: ' . $e->getMessage());
            }
        } else {
            // If no skills array is provided, detach all skills
            $user->skills()->detach();
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Don't allow deleting self
        if (auth()->id() == $id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }
}
