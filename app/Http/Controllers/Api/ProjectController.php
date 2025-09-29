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
     * 1. GET /api/projects - List all projects
     */
    public function index(Request $request): JsonResponse
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
     * 2. POST /api/projects - Create new project
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
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
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'project_manager_id' => $validated['project_manager_id'],
            'created_by' => auth()->id(),
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
     * 3. GET /api/projects/{id} - Get single project detail
     */
    public function show($id): JsonResponse
    {
        $project = Project::with(['projectManager', 'creator', 'members.role'])
                          ->find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Project retrieved successfully',
            'data' => $project
        ], 200);
    }

    /**
     * 4. PUT /api/projects/{id} - Update project
     */
    public function update(Request $request, $id): JsonResponse
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        $validated = $request->validate([
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
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'project_manager_id' => $validated['project_manager_id'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

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
     * 5. DELETE /api/projects/{id} - Delete project
     */
    public function destroy($id): JsonResponse
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        $project->members()->detach();
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully'
        ], 200);
    }

    /**
     * BONUS: PATCH /api/projects/{id}/status - Update project status (for members)
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
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
}