<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Project Statistics for this PM
        $my_projects = Project::where('project_manager_id', $user->id);
        
        $project_stats = [
            'total_projects' => $my_projects->count(),
            'completed_projects' => $my_projects->where('status', 'completed')->count(),
            'in_progress_projects' => $my_projects->where('status', 'in_progress')->count(),
            'planning_projects' => $my_projects->where('status', 'planning')->count(),
            'on_hold_projects' => $my_projects->where('status', 'on_hold')->count(),
            'cancelled_projects' => $my_projects->where('status', 'cancelled')->count(),
            'active_projects' => $my_projects->where('is_active', true)->count(),
        ];

        // Calculate completion rate
        $completion_rate = $project_stats['total_projects'] > 0 
            ? round(($project_stats['completed_projects'] / $project_stats['total_projects']) * 100, 1)
            : 0;

        // Recent Projects (last 5 that I manage)
        $recent_projects = Project::where('project_manager_id', $user->id)
            ->with(['creator', 'members'])
            ->latest()
            ->take(5)
            ->get();

        // My Team Members (from all my projects)
        $my_team_members = User::whereHas('memberProjects', function($query) use ($user) {
            $query->where('project_manager_id', $user->id);
        })->distinct()->get();

        // Overdue Projects
        $overdue_projects = Project::where('project_manager_id', $user->id)
            ->where('end_date', '<', now())
            ->where('status', '!=', 'completed')
            ->count();

        // Projects ending soon (within 7 days)
        $ending_soon_projects = Project::where('project_manager_id', $user->id)
            ->where('end_date', '>=', now())
            ->where('end_date', '<=', now()->addDays(7))
            ->where('status', '!=', 'completed')
            ->count();

        // Activity Summary
        $activity_summary = [
            'projects_created_this_month' => Project::where('project_manager_id', $user->id)
                ->where('created_at', '>=', Carbon::now()->subMonth())
                ->count(),
            'projects_completed_this_month' => Project::where('project_manager_id', $user->id)
                ->where('status', 'completed')
                ->where('updated_at', '>=', Carbon::now()->subMonth())
                ->count(),
        ];

        return view('project-manager.dashboard', compact(
            'project_stats',
            'completion_rate',
            'recent_projects',
            'my_team_members',
            'overdue_projects',
            'ending_soon_projects',
            'activity_summary'
        ));
    }
}