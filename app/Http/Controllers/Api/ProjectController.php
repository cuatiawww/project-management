<?php
// app/Http/Controllers/Api/ProjectController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /**
     * 1. GET /api/v1/projects - List all projects
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $query = Project::with(['projectManager', 'creator', 'members']);

        // Filter berdasarkan role
        if ($user->isMember()) {
            // Member: hanya lihat project yang dia ikuti
            $query->whereHas('members', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('is_active', true);
        } elseif ($user->isProjectManager()) {
            // PM: hanya lihat project yang dia manage
            $query->where('project_manager_id', $user->id);
        }
        // Admin: lihat semua project (no filter)

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
        if ($request->filled('project_manager_id')) {
            $query->where('project_manager_id', $request->project_manager_id);
        }

        $projects = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Projects retrieved successfully',
            'data' => $projects
        ], 200);
    }

    /**
     * 2. POST /api/v1/projects - Create new project
     */
    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();

        // Hanya Admin & PM yang bisa create
        if (!$user->isAdmin() && !$user->isProjectManager()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Admin and Project Manager can create projects.'
            ], 403);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:planning,in_progress,completed,on_hold,cancelled'],
            'start_date' => ['nullable', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'members' => ['nullable', 'array'],
            'members.*' => ['exists:users,id'],
            'is_active' => ['boolean'],
        ];

        // Admin wajib pilih PM, PM otomatis jadi PM
        if ($user->isAdmin()) {
            $rules['project_manager_id'] = ['required', 'exists:users,id'];
        }

        $validated = $request->validate($rules);

        // Tentukan PM berdasarkan role
        $projectManagerId = $user->isAdmin() 
            ? $validated['project_manager_id'] 
            : $user->id;

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'project_manager_id' => $projectManagerId,
            'created_by' => $user->id,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Add members to project
        if (!empty($validated['members'])) {
            $membersData = [];
            foreach ($validated['members'] as $memberId) {
                $membersData[$memberId] = [
                    'role' => 'member',
                    'joined_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $project->members()->attach($membersData);
        }

        $project->load(['projectManager', 'creator', 'members']);

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }

    /**
     * 3. GET /api/v1/projects/{id} - Get single project detail
     */
    public function show($id): JsonResponse
    {
        $user = auth()->user();
        $project = Project::with(['projectManager', 'creator', 'members.role'])->find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        // Authorization check
        if ($user->isMember()) {
            if (!$project->members->contains('id', $user->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to this project'
                ], 403);
            }
        } elseif ($user->isProjectManager()) {
            if ($project->project_manager_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. This is not your project.'
                ], 403);
            }
        }
        // Admin: bisa lihat semua

        return response()->json([
            'success' => true,
            'message' => 'Project retrieved successfully',
            'data' => $project
        ], 200);
    }

    /**
     * 4. PUT /api/v1/projects/{id} - Update project
     */
    public function update(Request $request, $id): JsonResponse
    {
        $user = auth()->user();
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        // Authorization check
        if ($user->isMember()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Members cannot update projects.'
            ], 403);
        }

        if ($user->isProjectManager() && $project->project_manager_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. This is not your project.'
            ], 403);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:planning,in_progress,completed,on_hold,cancelled'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'members' => ['nullable', 'array'],
            'members.*' => ['exists:users,id'],
            'is_active' => ['boolean'],
        ];

        // Admin bisa ubah PM, PM tidak bisa
        if ($user->isAdmin()) {
            $rules['project_manager_id'] = ['required', 'exists:users,id'];
        }

        $validated = $request->validate($rules);

        $updateData = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ];

        // Admin bisa ubah PM
        if ($user->isAdmin() && isset($validated['project_manager_id'])) {
            $updateData['project_manager_id'] = $validated['project_manager_id'];
        }

        $project->update($updateData);

        // Sync members
        if (isset($validated['members'])) {
            $membersData = [];
            foreach ($validated['members'] as $memberId) {
                $existing = $project->members()->where('user_id', $memberId)->first();
                $membersData[$memberId] = [
                    'role' => 'member',
                    'joined_at' => $existing ? $existing->pivot->joined_at : now(),
                ];
            }
            $project->members()->sync($membersData);
        }

        $project->load(['projectManager', 'creator', 'members']);

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'data' => $project
        ], 200);
    }

    /**
     * 5. DELETE /api/v1/projects/{id} - Delete project
     */
    public function destroy($id): JsonResponse
    {
        $user = auth()->user();
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        // Authorization check
        if ($user->isMember()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Members cannot delete projects.'
            ], 403);
        }

        if ($user->isProjectManager() && $project->project_manager_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. This is not your project.'
            ], 403);
        }

        $project->members()->detach();
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully'
        ], 200);
    }

    /**
     * BONUS: PATCH /api/v1/projects/{id}/status - Update project status (for members)
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user->isMember()) {
            return response()->json([
                'success' => false,
                'message' => 'Only members can update project status via this endpoint'
            ], 403);
        }

        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        if (!$project->members->contains('id', $user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not assigned to this project'
            ], 403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:planning,in_progress,completed,on_hold,cancelled'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $oldStatus = $project->status;
        $project->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Project status updated successfully',
            'data' => [
                'project_id' => $project->id,
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
                'updated_at' => $project->updated_at
            ]
        ], 200);
    }

    /**
     * BONUS: GET /api/v1/projects/statistics - Get project statistics
     */
    public function statistics(): JsonResponse
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $stats = [
                'total_projects' => Project::count(),
                'active_projects' => Project::where('is_active', true)->count(),
                'completed_projects' => Project::where('status', 'completed')->count(),
                'in_progress_projects' => Project::where('status', 'in_progress')->count(),
                'planning_projects' => Project::where('status', 'planning')->count(),
                'on_hold_projects' => Project::where('status', 'on_hold')->count(),
                'cancelled_projects' => Project::where('status', 'cancelled')->count(),
            ];
        } elseif ($user->isProjectManager()) {
            $stats = [
                'total_projects' => Project::where('project_manager_id', $user->id)->count(),
                'active_projects' => Project::where('project_manager_id', $user->id)->where('is_active', true)->count(),
                'completed_projects' => Project::where('project_manager_id', $user->id)->where('status', 'completed')->count(),
                'in_progress_projects' => Project::where('project_manager_id', $user->id)->where('status', 'in_progress')->count(),
            ];
        } else {
            $stats = [
                'total_projects' => Project::whereHas('members', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->count(),
                'completed_projects' => Project::whereHas('members', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->where('status', 'completed')->count(),
                'in_progress_projects' => Project::whereHas('members', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->where('status', 'in_progress')->count(),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $stats
        ], 200);
    }
}