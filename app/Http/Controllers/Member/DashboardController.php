<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Base query untuk projects member ini
        $baseQuery = function() use ($user) {
            return Project::whereHas('members', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('is_active', true);
        };

        // Project Statistics for this Member
        $project_stats = [
            'total_projects' => $baseQuery()->count(),
            'completed_projects' => $baseQuery()->where('status', 'completed')->count(),
            'in_progress_projects' => $baseQuery()->where('status', 'in_progress')->count(),
            'planning_projects' => $baseQuery()->where('status', 'planning')->count(),
            'on_hold_projects' => $baseQuery()->where('status', 'on_hold')->count(),
            'cancelled_projects' => $baseQuery()->where('status', 'cancelled')->count(),
        ];

        // Recent Projects (last 5 where I'm a member)
        $recent_projects = $baseQuery()
            ->with(['projectManager', 'creator'])
            ->latest()
            ->take(5)
            ->get();

        // Active Projects (current projects I'm working on - exclude completed and cancelled)
        $active_projects = $baseQuery()
            ->whereIn('status', ['planning', 'in_progress', 'on_hold'])
            ->with(['projectManager'])
            ->get();

        // Overdue Projects (that I'm part of)
        $overdue_projects = $baseQuery()
            ->where('end_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        // Projects ending soon (within 7 days)
        $ending_soon_projects = $baseQuery()
            ->where('end_date', '>=', now())
            ->where('end_date', '<=', now()->addDays(7))
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        // Activity Summary
        $activity_summary = [
            'joined_projects_this_month' => Project::whereHas('members', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('project_members.created_at', '>=', Carbon::now()->subMonth());
            })->count(),
            'completed_projects_this_month' => $baseQuery()
                ->where('status', 'completed')
                ->where('updated_at', '>=', Carbon::now()->subMonth())
                ->count(),
        ];

        return view('member.dashboard', compact(
            'project_stats',
            'recent_projects',
            'active_projects',
            'overdue_projects',
            'ending_soon_projects',
            'activity_summary'
        ));
    }
}