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
}