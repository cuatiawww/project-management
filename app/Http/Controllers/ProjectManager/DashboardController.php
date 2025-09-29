<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get all projects managed by this PM
        $projects = Project::where('project_manager_id', $user->id)->get();
        
        // Calculate project statistics
        $project_stats = [
            'total_projects' => $projects->count(),
            'active_projects' => $projects->whereIn('status', ['planning', 'in_progress'])->count(),
            'completed_projects' => $projects->where('status', 'completed')->count(),
            'in_progress_projects' => $projects->where('status', 'in_progress')->count(),
            'planning_projects' => $projects->where('status', 'planning')->count(),
            'on_hold_projects' => $projects->where('status', 'on_hold')->count(),
            'cancelled_projects' => $projects->where('status', 'cancelled')->count(),
        ];
        
        // Calculate completion rate
        $completion_rate = $project_stats['total_projects'] > 0 
            ? round(($project_stats['completed_projects'] / $project_stats['total_projects']) * 100, 1)
            : 0;
        
        // Calculate overdue projects (past end_date and not completed)
        $overdue_projects = $projects->where('status', '!=', 'completed')
            ->filter(function($project) {
                return $project->end_date && Carbon::parse($project->end_date)->isPast();
            })->count();
        
        // Calculate ending soon projects (within 7 days)
        $ending_soon_projects = $projects->where('status', '!=', 'completed')
            ->filter(function($project) {
                if (!$project->end_date) return false;
                $endDate = Carbon::parse($project->end_date);
                return $endDate->isFuture() && $endDate->diffInDays(now()) <= 7;
            })->count();
        
        // Activity summary this month
        $activity_summary = [
            'projects_created_this_month' => Project::where('project_manager_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'projects_completed_this_month' => Project::where('project_manager_id', $user->id)
                ->where('status', 'completed')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count(),
        ];
        
        // Get recent projects (latest 5)
        $recent_projects = Project::where('project_manager_id', $user->id)
            ->with('members')
            ->latest()
            ->limit(5)
            ->get();
        
        // Get all unique team members from PM's projects
        $my_team_members = User::whereHas('memberProjects', function($query) use ($user) {
            $query->where('project_manager_id', $user->id);
        })->with(['memberProjects' => function($query) use ($user) {
            $query->where('project_manager_id', $user->id);
        }])->get();
        
        return view('project-manager.dashboard', compact(
            'project_stats',
            'completion_rate',
            'overdue_projects',
            'ending_soon_projects',
            'activity_summary',
            'recent_projects',
            'my_team_members'
        ));
    }
}