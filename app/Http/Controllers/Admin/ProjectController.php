<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::with(['projectManager', 'creator', 'members']);

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

        // Filter by project manager
        if ($request->filled('project_manager')) {
            $query->where('project_manager_id', $request->project_manager);
        }

        // Filter by active status
        if ($request->filled('active')) {
            $query->where('is_active', $request->active === 'active');
        }

        $projects = $query->latest()->paginate(12);
        $projectManagers = User::whereHas('role', function($q) {
            $q->whereIn('name', ['admin', 'project_manager']);
        })->get();

        return view('admin.projects.index', compact('projects', 'projectManagers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projectManagers = User::whereHas('role', function($q) {
            $q->whereIn('name', ['admin', 'project_manager']);
        })->get();
        
        $members = User::whereHas('role', function($q) {
            $q->where('name', 'member');
        })->get();

        return view('admin.projects.create', compact('projectManagers', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:planning,in_progress,completed,on_hold,cancelled'],
            'start_date' => ['nullable', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'project_manager_id' => ['required', 'exists:users,id'],
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
            'project_manager_id' => $request->project_manager_id,
            'created_by' => auth()->id(),
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

        return redirect()->route('admin.projects.index')
                        ->with('success', 'Project berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['projectManager', 'creator', 'members.role']);
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $projectManagers = User::whereHas('role', function($q) {
            $q->whereIn('name', ['admin', 'project_manager']);
        })->get();
        
        $members = User::whereHas('role', function($q) {
            $q->where('name', 'member');
        })->get();

        $project->load('members');

        return view('admin.projects.edit', compact('project', 'projectManagers', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:planning,in_progress,completed,on_hold,cancelled'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'project_manager_id' => ['required', 'exists:users,id'],
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
            'project_manager_id' => $request->project_manager_id,
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

        return redirect()->route('admin.projects.index')
                        ->with('success', 'Project berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->members()->detach(); 
        $project->delete();

        return redirect()->route('admin.projects.index')
                        ->with('success', 'Project berhasil dihapus.');
    }
}