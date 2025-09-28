<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
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

        return view('admin.dashboard', compact(
            'stats', 
            'recent_users', 
            'users_by_role', 
            'monthly_registrations',
            'activity_summary'
        ));
    }
}