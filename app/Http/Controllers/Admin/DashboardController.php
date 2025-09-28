<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Project;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // User Statistics
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::whereHas('role', function($q) {
                $q->where('name', 'admin');
            })->count(),
            'total_project_managers' => User::whereHas('role', function($q) {
                $q->where('name', 'project_manager');
            })->count(),
            'total_members' => User::whereHas('role', function($q) {
                $q->where('name', 'member');
            })->count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
        ];

        // Project Statistics
        $project_stats = [
            'total_projects' => Project::count(),
            'completed_projects' => Project::where('status', 'completed')->count(),
            'in_progress_projects' => Project::where('status', 'in_progress')->count(),
            'planning_projects' => Project::where('status', 'planning')->count(),
            'on_hold_projects' => Project::where('status', 'on_hold')->count(),
            'cancelled_projects' => Project::where('status', 'cancelled')->count(),
            'active_projects' => Project::where('is_active', true)->count(),
            'inactive_projects' => Project::where('is_active', false)->count(),
        ];

        // Calculate project completion rate
        $completion_rate = $project_stats['total_projects'] > 0 
            ? round(($project_stats['completed_projects'] / $project_stats['total_projects']) * 100, 1)
            : 0;

        // Recent Users (last 7 days)
        $recent_users = User::with('role')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->latest()
            ->take(10)
            ->get();

        // Users by Role for Chart
        $users_by_role = [
            'admins' => $stats['total_admins'],
            'project_managers' => $stats['total_project_managers'], 
            'members' => $stats['total_members']
        ];

        // Monthly Registration Trend (last 6 months)
        $monthly_registrations = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = User::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
            $monthly_registrations[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }

        // Recent Activity Summary
        $activity_summary = [
            'new_users_today' => User::whereDate('created_at', Carbon::today())->count(),
            'new_users_week' => User::where('created_at', '>=', Carbon::now()->subWeek())->count(),
            'new_users_month' => User::where('created_at', '>=', Carbon::now()->subMonth())->count(),
        ];

        // Recent Projects (last 5)
        $recent_projects = Project::with(['projectManager', 'creator'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'project_stats',
            'completion_rate',
            'recent_users', 
            'recent_projects',
            'users_by_role', 
            'monthly_registrations',
            'activity_summary'
        ));
    }
}