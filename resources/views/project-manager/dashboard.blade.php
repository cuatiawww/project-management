@extends('layouts.admin')

@section('title', 'Dashboard Project Manager')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard Project Manager</h1>
                <p class="text-sm md:text-base text-gray-600">Selamat datang kembali, {{ Auth::user()->name }}!</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <a href="{{ route('pm.projects.create') }}" 
                   class="inline-flex items-center justify-center px-3 md:px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Buat Project
                </a>
                <a href="{{ route('pm.projects.index') }}" 
                   class="inline-flex items-center justify-center px-3 md:px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Kelola Project
                </a>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    @if($overdue_projects > 0 || $ending_soon_projects > 0)
    <div class="mb-6 md:mb-8 space-y-3 md:space-y-4">
        @if($overdue_projects > 0)
        <div class="bg-red-50 border-l-4 border-red-400 p-3 md:p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs md:text-sm text-red-700">
                        <strong>Perhatian!</strong> {{ $overdue_projects }} project sudah melewati deadline.
                    </p>
                </div>
            </div>
        </div>
        @endif

        @if($ending_soon_projects > 0)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 md:p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs md:text-sm text-yellow-700">
                        <strong>Reminder!</strong> {{ $ending_soon_projects }} project akan berakhir dalam 7 hari.
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Total Projects -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-4 md:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="ml-3 md:ml-4 flex-1 min-w-0">
                        <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Total Project</dt>
                        <dd class="flex items-baseline flex-wrap gap-1">
                            <div class="text-xl md:text-2xl font-semibold text-gray-900">{{ $project_stats['total_projects'] }}</div>
                            <div class="text-xs font-semibold text-green-600">
                                +{{ $activity_summary['projects_created_this_month'] }}
                            </div>
                        </dd>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 md:px-5 py-2 md:py-3">
                <a href="{{ route('pm.projects.index') }}" class="text-xs md:text-sm font-medium text-blue-700 hover:text-blue-900">
                    Lihat semua →
                </a>
            </div>
        </div>

        <!-- Active Projects -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-4 md:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 md:ml-4 flex-1 min-w-0">
                        <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Aktif</dt>
                        <dd class="text-xl md:text-2xl font-semibold text-gray-900">{{ $project_stats['active_projects'] }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Projects -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-4 md:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 md:ml-4 flex-1 min-w-0">
                        <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Selesai</dt>
                        <dd class="flex items-baseline flex-wrap gap-1">
                            <div class="text-xl md:text-2xl font-semibold text-gray-900">{{ $project_stats['completed_projects'] }}</div>
                            <div class="text-xs font-semibold text-green-600">
                                +{{ $activity_summary['projects_completed_this_month'] }}
                            </div>
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Members -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-4 md:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 md:ml-4 flex-1 min-w-0">
                        <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Tim</dt>
                        <dd class="text-xl md:text-2xl font-semibold text-gray-900">{{ $my_team_members->count() }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Progress & Status -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Completion Circle -->
        <div class="bg-white shadow rounded-lg p-4 md:p-6">
            <h3 class="text-base md:text-lg font-medium text-gray-900 mb-4">Completion Rate</h3>
            <div class="flex items-center justify-center">
                <div class="relative w-24 h-24 md:w-32 md:h-32">
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
                    <span class="text-gray-600">Project Aktif:</span>
                    <span class="font-medium text-gray-900">{{ $project_stats['active_projects'] }}</span>
                </div>
            </div>
            <div class="bg-gray-50 -mx-4 md:-mx-6 -mb-4 md:-mb-6 px-4 md:px-6 py-3 mt-4 rounded-b-lg">
                <a href="{{ route('pm.projects.index') }}" class="text-xs md:text-sm font-medium text-indigo-700 hover:text-indigo-900">
                    Kelola project →
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Projects & Team -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Recent Projects -->
        <div class="lg:col-span-2 bg-white shadow rounded-lg">
            <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base md:text-lg font-medium text-gray-900">Project Terbaru</h3>
                <a href="{{ route('pm.projects.index') }}" class="text-xs md:text-sm font-medium text-indigo-600 hover:text-indigo-500">
                    Semua →
                </a>
            </div>
            <div class="p-4 md:p-6">
                @if($recent_projects->count() > 0)
                    <div class="space-y-3 md:space-y-4">
                        @foreach($recent_projects as $project)
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 md:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors gap-3">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="w-8 h-8 md:w-10 md:h-10 bg-indigo-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm md:text-base font-medium text-gray-900 truncate">{{ $project->name }}</h4>
                                        <p class="text-xs md:text-sm text-gray-600">{{ $project->members->count() }} member</p>
                                        <div class="flex items-center mt-1">
                                            <div class="w-16 md:w-20 bg-gray-200 rounded-full h-1.5 mr-2">
                                                <div class="h-1.5 rounded-full {{ str_replace('bg-', 'bg-', $project->progress_color) }}" 
                                                     style="width: {{ $project->progress }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $project->progress }}%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between sm:flex-col sm:items-end gap-2">
                                    <span class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs font-medium 
                                        @if($project->status == 'completed') bg-green-100 text-green-800
                                        @elseif($project->status == 'in_progress') bg-yellow-100 text-yellow-800
                                        @elseif($project->status == 'planning') bg-blue-100 text-blue-800
                                        @elseif($project->status == 'on_hold') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $project->status_label }}
                                    </span>
                                    <a href="{{ route('pm.projects.show', $project) }}" 
                                       class="text-xs text-indigo-600 hover:text-indigo-500 whitespace-nowrap">
                                        Detail →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada project</h3>
                        <div class="mt-4">
                            <a href="{{ route('pm.projects.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Buat Project
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Team Overview -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
                <h3 class="text-base md:text-lg font-medium text-gray-900">Tim Saya</h3>
            </div>
            <div class="p-4 md:p-6">
                @if($my_team_members->count() > 0)
                    <div class="space-y-3">
                        @foreach($my_team_members->take(5) as $member)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0 h-8 w-8 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium text-white">
                                        {{ substr($member->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $member->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $member->email }}</p>
                                </div>
                            </div>
                        @endforeach
                        @if($my_team_members->count() > 5)
                            <div class="text-center pt-2">
                                <p class="text-xs text-gray-500">+{{ $my_team_members->count() - 5 }} lainnya</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada tim</h3>
                        <p class="mt-1 text-xs text-gray-500">Buat project dan assign member</p>
                    </div>
                @endif
            </div>
            @if($my_team_members->count() > 0)
                <div class="bg-gray-50 px-4 md:px-6 py-3">
                    <a href="{{ route('pm.projects.index') }}" class="text-xs md:text-sm font-medium text-indigo-700 hover:text-indigo-900">
                        Kelola tim →
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection