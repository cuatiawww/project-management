<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display projects managed by this PM
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Project::where('project_manager_id', $user->id)
                       ->with(['creator', 'members']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by active status
        if ($request->filled('active')) {
            $query->where('is_active', $request->active === 'active');
        }

        $projects = $query->latest()->paginate(12);

        return view('project-manager.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        // PM can only assign members (not other PMs or admins)
        $members = User::whereHas('role', function($q) {
            $q->where('name', 'member');
        })->where('is_active', true)->get();

        return view('project-manager.projects.create', compact('members'));
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:planning,in_progress,completed,on_hold,cancelled'],
            'start_date' => ['nullable', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'members' => ['nullable', 'array'],
            'members.*' => ['exists:users,id'],
            'is_active' => ['boolean'],
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'project_manager_id' => $user->id, // PM assigns themselves
            'created_by' => $user->id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Add members to project
        if ($request->filled('members')) {
            $membersData = [];
            foreach ($request->members as $memberId) {
                $membersData[$memberId] = [
                    'role' => 'member',
                    'joined_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $project->members()->attach($membersData);
        }

        return redirect()->route('pm.projects.index')
                        ->with('success', 'Project berhasil dibuat.');
    }

    /**
     * Display the specified project
     */
    public function show(Project $project)
    {
        // Ensure PM can only view their own projects
        if ($project->project_manager_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this project.');
        }

        $project->load(['creator', 'members.role']);
        return view('project-manager.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project
     */
    public function edit(Project $project)
    {
        // Ensure PM can only edit their own projects
        if ($project->project_manager_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this project.');
        }

        $members = User::whereHas('role', function($q) {
            $q->where('name', 'member');
        })->where('is_active', true)->get();

        $project->load('members');

        return view('project-manager.projects.edit', compact('project', 'members'));
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, Project $project)
    {
        // Ensure PM can only update their own projects
        if ($project->project_manager_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this project.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:planning,in_progress,completed,on_hold,cancelled'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'members' => ['nullable', 'array'],
            'members.*' => ['exists:users,id'],
            'is_active' => ['boolean'],
        ]);

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Sync members
        if ($request->has('members')) {
            $membersData = [];
            foreach ($request->members ?? [] as $memberId) {
                $membersData[$memberId] = [
                    'role' => 'member',
                    'joined_at' => $project->members()->where('user_id', $memberId)->exists() 
                                   ? $project->members()->where('user_id', $memberId)->first()->pivot->joined_at
                                   : now(),
                ];
            }
            $project->members()->sync($membersData);
        } else {
            $project->members()->detach();
        }

        return redirect()->route('pm.projects.index')
                        ->with('success', 'Project berhasil diupdate.');
    }

    /**
     * Remove the specified project
     */
    public function destroy(Project $project)
    {
        // Ensure PM can only delete their own projects
        if ($project->project_manager_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this project.');
        }

        $project->members()->detach(); 
        $project->delete();

        return redirect()->route('pm.projects.index')
                        ->with('success', 'Project berhasil dihapus.');
    }
}