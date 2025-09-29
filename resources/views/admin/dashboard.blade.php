@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-sm md:text-base text-gray-600">Selamat datang kembali, {{ Auth::user()->name }}!</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <a href="{{ route('admin.users.create') }}" 
                   class="inline-flex items-center justify-center px-3 md:px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah User
                </a>
                <a href="{{ route('admin.projects.create') }}" 
                   class="inline-flex items-center justify-center px-3 md:px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Buat Project
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Total Users -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-4 md:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1 min-w-0">
                        <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Total Users</dt>
                        <dd class="flex items-baseline">
                            <div class="text-xl md:text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</div>
                            <div class="ml-2 text-xs font-semibold text-green-600 hidden sm:block">
                                +{{ $activity_summary['new_users_month'] }}
                            </div>
                        </dd>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 md:px-5 py-2 md:py-3">
                <a href="{{ route('admin.users.index') }}" class="text-xs md:text-sm font-medium text-blue-700 hover:text-blue-900">
                    Lihat semua →
                </a>
            </div>
        </div>

        <!-- Administrators -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-4 md:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1 min-w-0">
                        <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Administrators</dt>
                        <dd class="text-xl md:text-2xl font-semibold text-gray-900">{{ $stats['total_admins'] }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Managers -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-4 md:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1 min-w-0">
                        <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">PM</dt>
                        <dd class="text-xl md:text-2xl font-semibold text-gray-900">{{ $stats['total_project_managers'] }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Members -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-4 md:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1 min-w-0">
                        <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Members</dt>
                        <dd class="text-xl md:text-2xl font-semibold text-gray-900">{{ $stats['total_members'] }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Completion Circle -->
        <div class="bg-white shadow rounded-lg p-4 md:p-6">
            <h3 class="text-base md:text-lg font-medium text-gray-900 mb-4">Project Completion</h3>
            <div class="flex items-center justify-center">
                <div class="relative w-28 h-28 md:w-32 md:h-32">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" stroke="#E5E7EB" stroke-width="8" fill="none"/>
                        <circle cx="50" cy="50" r="40" stroke="#10B981" stroke-width="8" fill="none" 
                                stroke-dasharray="{{ 2.51 * $completion_rate }} 251" stroke-linecap="round"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-xl md:text-2xl font-bold text-gray-900">{{ $completion_rate }}%</div>
                            <div class="text-xs text-gray-500">Selesai</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 text-center">
                <p class="text-xs md:text-sm text-gray-600">
                    {{ $project_stats['completed_projects'] }} dari {{ $project_stats['total_projects'] }} project
                </p>
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="bg-white shadow rounded-lg lg:col-span-2 p-4 md:p-6">
            <h3 class="text-base md:text-lg font-medium text-gray-900 mb-4">Status Project</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">
                <div class="text-center p-3 md:p-4 bg-gray-50 rounded-lg">
                    <div class="text-lg md:text-2xl font-bold text-gray-900">{{ $project_stats['total_projects'] }}</div>
                    <div class="text-xs md:text-sm text-gray-600">Total</div>
                </div>
                <div class="text-center p-3 md:p-4 bg-green-50 rounded-lg">
                    <div class="text-lg md:text-2xl font-bold text-green-600">{{ $project_stats['completed_projects'] }}</div>
                    <div class="text-xs md:text-sm text-green-600">Selesai</div>
                </div>
                <div class="text-center p-3 md:p-4 bg-yellow-50 rounded-lg">
                    <div class="text-lg md:text-2xl font-bold text-yellow-600">{{ $project_stats['in_progress_projects'] }}</div>
                    <div class="text-xs md:text-sm text-yellow-600">Berjalan</div>
                </div>
                <div class="text-center p-3 md:p-4 bg-blue-50 rounded-lg">
                    <div class="text-lg md:text-2xl font-bold text-blue-600">{{ $project_stats['planning_projects'] }}</div>
                    <div class="text-xs md:text-sm text-blue-600">Planning</div>
                </div>
                <div class="text-center p-3 md:p-4 bg-gray-100 rounded-lg">
                    <div class="text-lg md:text-2xl font-bold text-gray-600">{{ $project_stats['on_hold_projects'] }}</div>
                    <div class="text-xs md:text-sm text-gray-600">Ditunda</div>
                </div>
                <div class="text-center p-3 md:p-4 bg-red-50 rounded-lg">
                    <div class="text-lg md:text-2xl font-bold text-red-600">{{ $project_stats['cancelled_projects'] }}</div>
                    <div class="text-xs md:text-sm text-red-600">Batal</div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex justify-between text-xs md:text-sm">
                    <span class="text-gray-600">Aktif:</span>
                    <span class="font-medium text-gray-900">{{ $project_stats['active_projects'] }}</span>
                </div>
                <div class="flex justify-between text-xs md:text-sm mt-1">
                    <span class="text-gray-600">Non-aktif:</span>
                    <span class="font-medium text-gray-900">{{ $project_stats['inactive_projects'] }}</span>
                </div>
            </div>
            <div class="bg-gray-50 -mx-4 md:-mx-6 -mb-4 md:-mb-6 px-4 md:px-6 py-3 mt-4 rounded-b-lg">
                <a href="{{ route('admin.projects.index') }}" class="text-xs md:text-sm font-medium text-indigo-700 hover:text-indigo-900">
                    Kelola semua project →
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-6">
        <!-- User Distribution -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
                <h3 class="text-base md:text-lg font-medium text-gray-900">User by Role</h3>
            </div>
            <div class="p-4 md:p-6 space-y-3 md:space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2 md:mr-3"></div>
                        <span class="text-xs md:text-sm text-gray-600">Admin</span>
                    </div>
                    <span class="text-xs md:text-sm font-medium text-gray-900">{{ $users_by_role['admins'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2 md:mr-3"></div>
                        <span class="text-xs md:text-sm text-gray-600">PM</span>
                    </div>
                    <span class="text-xs md:text-sm font-medium text-gray-900">{{ $users_by_role['project_managers'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-purple-500 rounded-full mr-2 md:mr-3"></div>
                        <span class="text-xs md:text-sm text-gray-600">Member</span>
                    </div>
                    <span class="text-xs md:text-sm font-medium text-gray-900">{{ $users_by_role['members'] }}</span>
                </div>
            </div>
        </div>

        <!-- Registration Activity -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
                <h3 class="text-base md:text-lg font-medium text-gray-900">Aktivitas</h3>
            </div>
            <div class="p-4 md:p-6 space-y-3 md:space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs md:text-sm text-gray-600">Hari ini</span>
                    <span class="text-lg md:text-2xl font-bold text-green-600">{{ $activity_summary['new_users_today'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs md:text-sm text-gray-600">Minggu ini</span>
                    <span class="text-lg md:text-2xl font-bold text-blue-600">{{ $activity_summary['new_users_week'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs md:text-sm text-gray-600">Bulan ini</span>
                    <span class="text-lg md:text-2xl font-bold text-purple-600">{{ $activity_summary['new_users_month'] }}</span>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white shadow rounded-lg md:col-span-2 lg:col-span-1">
            <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
                <h3 class="text-base md:text-lg font-medium text-gray-900">Status Sistem</h3>
            </div>
            <div class="p-4 md:p-6 space-y-3 md:space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs md:text-sm text-gray-600">Database</span>
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-xs md:text-sm font-medium text-green-600">Online</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs md:text-sm text-gray-600">Aktif</span>
                    <span class="text-xs md:text-sm font-medium text-gray-900">{{ $stats['active_users'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs md:text-sm text-gray-600">Non-aktif</span>
                    <span class="text-xs md:text-sm font-medium text-gray-900">{{ $stats['inactive_users'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Projects -->
    @if(isset($recent_projects) && $recent_projects->count() > 0)
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-base md:text-lg font-medium text-gray-900">Project Terbaru</h3>
            <a href="{{ route('admin.projects.index') }}" class="text-xs md:text-sm font-medium text-indigo-600 hover:text-indigo-500">
                Lihat semua →
            </a>
        </div>
        <div class="p-4 md:p-6 space-y-3 md:space-y-4">
            @foreach($recent_projects as $project)
                <div class="flex items-center justify-between p-3 md:p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center min-w-0 flex-1">
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-indigo-500 rounded-lg flex items-center justify-center mr-3 md:mr-4 flex-shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm md:text-base font-medium text-gray-900 truncate">{{ $project->name }}</h4>
                            <p class="text-xs md:text-sm text-gray-600 truncate">PM: {{ $project->projectManager->name }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs font-medium ml-2 flex-shrink-0
                        @if($project->status == 'completed') bg-green-100 text-green-800
                        @elseif($project->status == 'in_progress') bg-yellow-100 text-yellow-800
                        @elseif($project->status == 'planning') bg-blue-100 text-blue-800
                        @elseif($project->status == 'on_hold') bg-gray-100 text-gray-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ $project->status_label }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection