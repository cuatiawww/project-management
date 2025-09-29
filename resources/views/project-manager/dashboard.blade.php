    @extends('layouts.admin')

@section('title', 'Dashboard Project Manager')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Dashboard Project Manager</h1>
                <p class="text-gray-600">Selamat datang kembali, {{ Auth::user()->name }}!</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('pm.projects.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Buat Project Baru
                </a>
                <a href="{{ route('pm.projects.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
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
    <div class="mb-8">
        @if($overdue_projects > 0)
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        <strong>Perhatian!</strong> Anda memiliki {{ $overdue_projects }} project yang sudah melewati deadline.
                    </p>
                </div>
            </div>
        </div>
        @endif

        @if($ending_soon_projects > 0)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Reminder!</strong> Anda memiliki {{ $ending_soon_projects }} project yang akan berakhir dalam 7 hari.
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Projects Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Project Saya</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $project_stats['total_projects'] }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    +{{ $activity_summary['projects_created_this_month'] }} bulan ini
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('pm.projects.index') }}" class="font-medium text-blue-700 hover:text-blue-900">
                        Lihat semua project
                    </a>
                </div>
            </div>
        </div>

        <!-- Active Projects Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Project Aktif</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $project_stats['active_projects'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Projects Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Project Selesai</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $project_stats['completed_projects'] }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    +{{ $activity_summary['projects_completed_this_month'] }} bulan ini
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Members Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Team Members</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $my_team_members->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Progress & Status Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Project Completion Circle -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Project Completion Rate</h3>
                <div class="flex items-center justify-center">
                    <div class="relative w-32 h-32">
                        <!-- Background Circle -->
                        <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="40" stroke="#E5E7EB" stroke-width="8" fill="none"/>
                            <!-- Progress Circle -->
                            <circle cx="50" cy="50" r="40" 
                                    stroke="#10B981" 
                                    stroke-width="8" 
                                    fill="none" 
                                    stroke-dasharray="{{ 2.51 * $completion_rate }} 251" 
                                    stroke-linecap="round" 
                                    class="transition-all duration-1000 ease-out"/>
                        </svg>
                        <!-- Percentage Text -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $completion_rate }}%</div>
                                <div class="text-xs text-gray-500">Selesai</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        {{ $project_stats['completed_projects'] }} dari {{ $project_stats['total_projects'] }} project selesai
                    </p>
                </div>
            </div>
        </div>

        <!-- Project Status Breakdown -->
        <div class="bg-white overflow-hidden shadow rounded-lg lg:col-span-2">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Status Project Saya</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <!-- Total Projects -->
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900">{{ $project_stats['total_projects'] }}</div>
                        <div class="text-sm text-gray-600">Total Project</div>
                    </div>

                    <!-- Completed -->
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $project_stats['completed_projects'] }}</div>
                        <div class="text-sm text-green-600">Selesai</div>
                    </div>

                    <!-- In Progress -->
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">{{ $project_stats['in_progress_projects'] }}</div>
                        <div class="text-sm text-yellow-600">Sedang Berjalan</div>
                    </div>

                    <!-- Planning -->
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $project_stats['planning_projects'] }}</div>
                        <div class="text-sm text-blue-600">Perencanaan</div>
                    </div>

                    <!-- On Hold -->
                    <div class="text-center p-4 bg-gray-100 rounded-lg">
                        <div class="text-2xl font-bold text-gray-600">{{ $project_stats['on_hold_projects'] }}</div>
                        <div class="text-sm text-gray-600">Ditunda</div>
                    </div>

                    <!-- Cancelled -->
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">{{ $project_stats['cancelled_projects'] }}</div>
                        <div class="text-sm text-red-600">Dibatalkan</div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Project Aktif:</span>
                        <span class="font-medium text-gray-900">{{ $project_stats['active_projects'] }}</span>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3">
                <div class="text-sm">
                    <a href="{{ route('pm.projects.index') }}" class="font-medium text-indigo-700 hover:text-indigo-900">
                        Kelola semua project saya →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Projects -->
        <div class="lg:col-span-2 bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Project Terbaru Saya</h3>
                    <a href="{{ route('pm.projects.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Lihat semua →
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($recent_projects->count() > 0)
                    <div class="space-y-4">
                        @foreach($recent_projects as $project)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $project->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $project->members->count() }} member tim</p>
                                        <div class="flex items-center mt-1">
                                            <div class="w-16 bg-gray-200 rounded-full h-1.5 mr-2">
                                                <div class="h-1.5 rounded-full {{ str_replace('bg-', 'bg-', $project->progress_color) }}" 
                                                     style="width: {{ $project->progress }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $project->progress }}%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        @if($project->status == 'completed') bg-green-100 text-green-800
                                        @elseif($project->status == 'in_progress') bg-yellow-100 text-yellow-800
                                        @elseif($project->status == 'planning') bg-blue-100 text-blue-800
                                        @elseif($project->status == 'on_hold') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $project->status_label }}
                                    </span>
                                    <div class="mt-1">
                                        <a href="{{ route('pm.projects.show', $project) }}" 
                                           class="text-xs text-indigo-600 hover:text-indigo-500">
                                            Lihat Detail →
                                        </a>
                                    </div>
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
                        <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat project baru</p>
                        <div class="mt-4">
                            <a href="{{ route('pm.projects.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Buat Project Pertama
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Team Members Overview -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Tim Saya</h3>
            </div>
            <div class="p-6">
                @if($my_team_members->count() > 0)
                    <div class="space-y-3">
                        @foreach($my_team_members->take(6) as $member)
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
                        @if($my_team_members->count() > 6)
                            <div class="text-center pt-2">
                                <p class="text-xs text-gray-500">+{{ $my_team_members->count() - 6 }} member lainnya</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada tim</h3>
                        <p class="mt-1 text-xs text-gray-500">Buat project dan assign member untuk membangun tim</p>
                    </div>
                @endif
            </div>
            @if($my_team_members->count() > 0)
                <div class="bg-gray-50 px-6 py-3">
                    <div class="text-sm">
                        <a href="{{ route('pm.projects.index') }}" class="font-medium text-indigo-700 hover:text-indigo-900">
                            Kelola tim →
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Project Performance -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Performance Overview</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Projects This Month -->
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Project Dibuat Bulan Ini</p>
                                <p class="text-xs text-gray-600">Produktivitas pembuatan project</p>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-blue-600">{{ $activity_summary['projects_created_this_month'] }}</div>
                    </div>

                    <!-- Completed This Month -->
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Project Selesai Bulan Ini</p>
                                <p class="text-xs text-gray-600">Rate penyelesaian project</p>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-green-600">{{ $activity_summary['projects_completed_this_month'] }}</div>
                    </div>

                    <!-- Team Members -->
                    <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Total Team Members</p>
                                <p class="text-xs text-gray-600">Anggota tim dari semua project</p>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-purple-600">{{ $my_team_members->count() }}</div>
                    </div>

                    <!-- Project Completion Rate -->
                    <div class="flex items-center justify-between p-4 bg-orange-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Success Rate</p>
                                <p class="text-xs text-gray-600">Tingkat keberhasilan project</p>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-orange-600">{{ $completion_rate }}%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Workspace -->
        <div class="space-y-6">
            <!-- Quick Actions Card -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('pm.projects.create') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Buat Project Baru
                    </a>
                    <a href="{{ route('pm.projects.index') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Kelola Project
                    </a>
                    <a href="#" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-purple-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Kelola Tim
                    </a>
                </div>
            </div>

            <!-- Project Status Summary -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Status Overview</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Active vs Inactive -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Project Aktif</span>
                            <div class="flex items-center">
                                <span class="text-lg font-bold text-green-600 mr-2">{{ $project_stats['active_projects'] }}</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    Aktif
                                </span>
                            </div>
                        </div>

                        <!-- Progress Distribution -->
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Perencanaan</span>
                                <span class="font-medium text-blue-600">{{ $project_stats['planning_projects'] }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Sedang Berjalan</span>
                                <span class="font-medium text-yellow-600">{{ $project_stats['in_progress_projects'] }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Ditunda</span>
                                <span class="font-medium text-gray-600">{{ $project_stats['on_hold_projects'] }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Selesai</span>
                                <span class="font-medium text-green-600">{{ $project_stats['completed_projects'] }}</span>
                            </div>
                        </div>

                        <!-- Timeline Alerts -->
                        @if($overdue_projects > 0 || $ending_soon_projects > 0)
                            <div class="pt-4 border-t border-gray-200">
                                @if($overdue_projects > 0)
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-red-600">Terlambat</span>
                                        <span class="text-sm font-bold text-red-600">{{ $overdue_projects }}</span>
                                    </div>
                                @endif
                                @if($ending_soon_projects > 0)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-yellow-600">Deadline < 7 hari</span>
                                        <span class="text-sm font-bold text-yellow-600">{{ $ending_soon_projects }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Personal Workspace Section -->
    @if($my_team_members->count() > 0)
    <div class="mt-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Tim Members Overview</h3>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ $my_team_members->count() }} Members
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($my_team_members as $member)
                        <div class="flex items-center p-4 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex-shrink-0 h-10 w-10 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-white">
                                    {{ substr($member->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $member->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $member->email }}</p>
                                <p class="text-xs text-purple-600 font-medium">
                                    {{ $member->memberProjects->where('project_manager_id', Auth::id())->count() }} project
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3">
                <div class="text-sm">
                    <a href="{{ route('pm.projects.index') }}" class="font-medium text-indigo-700 hover:text-indigo-900">
                        Kelola assignment member →
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection