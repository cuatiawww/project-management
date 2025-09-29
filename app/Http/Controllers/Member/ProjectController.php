<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display projects where this member is assigned
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Project::whereHas('members', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('is_active', true)
          ->with(['projectManager', 'creator', 'members']);

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

        $projects = $query->latest()->paginate(12);

        return view('member.projects.index', compact('projects'));
    }

    /**
     * Display the specified project (read-only for members)
     */
    public function show(Project $project)
    {
        $user = auth()->user();
        
        // Ensure member can only view projects they're assigned to
        if (!$project->members->contains('id', $user->id)) {
            abort(403, 'You are not assigned to this project.');
        }

        $project->load(['projectManager', 'creator', 'members.role']);
        return view('member.projects.show', compact('project'));
    }

    /**
     * Update project status by member
     */
    public function updateStatus(Request $request, Project $project)
    {
        $user = auth()->user();
        
        // Ensure member can only update status for projects they're assigned to
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