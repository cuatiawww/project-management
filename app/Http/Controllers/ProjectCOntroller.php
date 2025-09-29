<?php

namespace App\Http\Controllers;

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
        $user = auth()->user();
        
        // Base query with eager loading
        $query = Project::with(['projectManager', 'creator', 'members']);

        // Filter based on role
        if ($user->isMember()) {
            // Members only see projects they're assigned to
            $query->whereHas('members', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('is_active', true);
        } elseif ($user->isProjectManager()) {
            // PMs only see their own projects
            $query->where('project_manager_id', $user->id);
        }
        // Admin sees all projects (no filter)

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

        // Filter by project manager (Admin only)
        if ($user->isAdmin() && $request->filled('project_manager')) {
            $query->where('project_manager_id', $request->project_manager);
        }

        // Filter by active status (Admin & PM only)
        if (!$user->isMember() && $request->filled('active')) {
            $query->where('is_active', $request->active === 'active');
        }

        $projects = $query->latest()->paginate(12);
        
        // Get project managers list for admin filter
        $projectManagers = $user->isAdmin() 
            ? User::whereHas('role', function($q) {
                $q->whereIn('name', ['admin', 'project_manager']);
              })->get()
            : null;

        return view('projects.index', compact('projects', 'projectManagers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        // Only Admin and PM can create projects
        if ($user->isMember()) {
            abort(403, 'Members cannot create projects.');
        }

        // Get project managers (Admin only, PM will be auto-assigned)
        $projectManagers = $user->isAdmin()
            ? User::whereHas('role', function($q) {
                $q->whereIn('name', ['admin', 'project_manager']);
              })->get()
            : null;
        
        // Get available members
        $members = User::whereHas('role', function($q) {
            $q->where('name', 'member');
        })->where('is_active', true)->get();

        return view('projects.create', compact('projectManagers', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Only Admin and PM can create projects
        if ($user->isMember()) {
            abort(403, 'Members cannot create projects.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:planning,in_progress,completed,on_hold,cancelled'],
            'start_date' => ['nullable', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'project_manager_id' => $user->isAdmin() ? ['required', 'exists:users,id'] : ['nullable'],
            'members' => ['nullable', 'array'],
            'members.*' => ['exists:users,id'],
            'is_active' => ['boolean'],
        ]);

        // Determine project manager
        $projectManagerId = $user->isAdmin() 
            ? $request->project_manager_id 
            : $user->id;

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'project_manager_id' => $projectManagerId,
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

        $routePrefix = $user->isAdmin() ? 'admin' : 'pm';
        return redirect()->route("{$routePrefix}.projects.index")
                        ->with('success', 'Project berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $user = auth()->user();
        
        // Check access based on role
        if ($user->isMember()) {
            // Members can only view projects they're assigned to
            if (!$project->members->contains('id', $user->id)) {
                abort(403, 'You are not assigned to this project.');
            }
        } elseif ($user->isProjectManager()) {
            // PMs can only view their own projects
            if ($project->project_manager_id !== $user->id) {
                abort(403, 'Unauthorized access to this project.');
            }
        }
        // Admin can view all projects

        $project->load(['projectManager', 'creator', 'members.role']);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $user = auth()->user();
        
        // Only Admin and PM can edit
        if ($user->isMember()) {
            abort(403, 'Members cannot edit projects.');
        }

        // PM can only edit their own projects
        if ($user->isProjectManager() && $project->project_manager_id !== $user->id) {
            abort(403, 'Unauthorized access to this project.');
        }

        // Get project managers (Admin only)
        $projectManagers = $user->isAdmin()
            ? User::whereHas('role', function($q) {
                $q->whereIn('name', ['admin', 'project_manager']);
              })->get()
            : null;
        
        // Get available members
        $members = User::whereHas('role', function($q) {
            $q->where('name', 'member');
        })->where('is_active', true)->get();

        $project->load('members');

        return view('projects.edit', compact('project', 'projectManagers', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $user = auth()->user();
        
        // Only Admin and PM can update
        if ($user->isMember()) {
            abort(403, 'Members cannot update projects.');
        }

        // PM can only update their own projects
        if ($user->isProjectManager() && $project->project_manager_id !== $user->id) {
            abort(403, 'Unauthorized access to this project.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:planning,in_progress,completed,on_hold,cancelled'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'project_manager_id' => $user->isAdmin() ? ['required', 'exists:users,id'] : ['nullable'],
            'members' => ['nullable', 'array'],
            'members.*' => ['exists:users,id'],
            'is_active' => ['boolean'],
        ]);

        $updateData = [
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Only admin can change PM
        if ($user->isAdmin()) {
            $updateData['project_manager_id'] = $request->project_manager_id;
        }

        $project->update($updateData);

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

        $routePrefix = $user->isAdmin() ? 'admin' : 'pm';
        return redirect()->route("{$routePrefix}.projects.index")
                        ->with('success', 'Project berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $user = auth()->user();
        
        // Only Admin and PM can delete
        if ($user->isMember()) {
            abort(403, 'Members cannot delete projects.');
        }

        // PM can only delete their own projects
        if ($user->isProjectManager() && $project->project_manager_id !== $user->id) {
            abort(403, 'Unauthorized access to this project.');
        }

        $project->members()->detach(); 
        $project->delete();

        $routePrefix = $user->isAdmin() ? 'admin' : 'pm';
        return redirect()->route("{$routePrefix}.projects.index")
                        ->with('success', 'Project berhasil dihapus.');
    }

    /**
     * Update project status by member
     */
    public function updateStatus(Request $request, Project $project)
    {
        $user = auth()->user();
        
        // Only members can use this method
        if (!$user->isMember()) {
            abort(403, 'Only members can update status through this method.');
        }
        
        // Ensure member is assigned to this project
        if (!$project->members->contains('id', $user->id)) {
            abort(403, 'You are not assigned to this project.');
        }

        $request->validate([
            'status' => ['required', 'in:planning,in_progress,completed,on_hold,cancelled'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $oldStatus = $project->status;
        $newStatus = $request->status;

        // Update project status
        $project->update([
            'status' => $newStatus
        ]);

        // Status labels untuk pesan
        $statusLabels = [
            'planning' => 'Perencanaan',
            'in_progress' => 'Sedang Berjalan', 
            'completed' => 'Selesai',
            'on_hold' => 'Ditunda',
            'cancelled' => 'Dibatalkan'
        ];

        $message = "Status project berhasil diubah dari '{$statusLabels[$oldStatus]}' ke '{$statusLabels[$newStatus]}'";
        
        if ($request->filled('notes')) {
            $message .= " dengan catatan: " . $request->notes;
        }

        return redirect()->route('member.projects.show', $project)
                        ->with('success', $message);
    }
}