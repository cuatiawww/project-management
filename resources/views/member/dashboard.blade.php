@extends('layouts.admin')

@section('title', 'Member Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard Member</h1>
                <p class="text-sm md:text-base text-gray-600">Selamat datang kembali, {{ Auth::user()->name }}!</p>
            </div>
            <div>
                <a href="{{ route('member.projects.index') }}" 
                   class="inline-flex items-center justify-center px-3 md:px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition w-full sm:w-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Lihat Semua Project
                </a>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    @if(($overdue_projects ?? 0) > 0 || ($ending_soon_projects ?? 0) > 0)
    <div class="mb-6 md:mb-8 space-y-3 md:space-y-4">
        @if(($overdue_projects ?? 0) > 0)
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

        @if(($ending_soon_projects ?? 0) > 0)
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

    <!-- Stats Cards -->
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
                        <dd class="text-xl md:text-2xl font-semibold text-gray-900">{{ $project_stats['total_projects'] ?? 0 }}</dd>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 md:px-5 py-2 md:py-3">
                <a href="{{ route('member.projects.index') }}" class="text-xs md:text-sm font-medium text-blue-700 hover:text-blue-900">
                    Lihat semua →
                </a>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-4 md:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 md:ml-4 flex-1 min-w-0">
                        <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Selesai</dt>
                        <dd class="text-xl md:text-2xl font-semibold text-gray-900">{{ $project_stats['completed_projects'] ?? 0 }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-4 md:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 md:ml-4 flex-1 min-w-0">
                        <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Berjalan</dt>
                        <dd class="text-xl md:text-2xl font-semibold text-gray-900">{{ $project_stats['in_progress_projects'] ?? 0 }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Planning -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-4 md:p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-3 md:ml-4 flex-1 min-w-0">
                        <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Planning</dt>
                        <dd class="text-xl md:text-2xl font-semibold text-gray-900">{{ $project_stats['planning_projects'] ?? 0 }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8 mb-6 md:mb-8">
        <!-- Active Projects -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
                <h3 class="text-base md:text-lg font-medium text-gray-900">Project Aktif Saya</h3>
            </div>
            <div class="p-4 md:p-6">
                @if(isset($active_projects) && $active_projects->count() > 0)
                    <div class="space-y-3 md:space-y-4">
                        @foreach($active_projects as $project)
                            <div class="border border-gray-200 rounded-lg p-3 md:p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between mb-2 gap-2">
                                    <h4 class="font-medium text-sm md:text-base text-gray-900 flex-1">{{ $project->name }}</h4>
                                    <span class="inline-flex items-center px-2 md:px-2.5 py-0.5 rounded-full text-xs font-medium flex-shrink-0
                                        {{ $project->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $project->status_label }}
                                    </span>
                                </div>
                                <div class="flex items-center text-xs md:text-sm text-gray-600 mb-2">
                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="truncate">PM: {{ $project->projectManager->name }}</span>
                                </div>
                                <!-- Progress Bar -->
                                <div class="mt-3">
                                    <div class="flex justify-between text-xs md:text-sm mb-1">
                                        <span class="text-gray-600">Progress</span>
                                        <span class="font-medium">{{ $project->progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ str_replace('bg-', 'bg-', $project->progress_color) }}" 
                                             style="width: {{ $project->progress }}%"></div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('member.projects.show', $project) }}" 
                                       class="text-xs md:text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                        Lihat Detail →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-10 w-10 md:h-12 md:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada project aktif</h3>
                        <p class="mt-1 text-xs md:text-sm text-gray-500">Anda belum memiliki project yang sedang berjalan.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Projects -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-base md:text-lg font-medium text-gray-900">Project Terbaru</h3>
                    <a href="{{ route('member.projects.index') }}" class="text-xs md:text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Semua →
                    </a>
                </div>
            </div>
            <div class="p-4 md:p-6">
                @if(isset($recent_projects) && $recent_projects->count() > 0)
                    <div class="space-y-3 md:space-y-4">
                        @foreach($recent_projects as $project)
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 md:p-4 bg-gray-50 rounded-lg gap-3">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="w-8 h-8 md:w-10 md:h-10 bg-indigo-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-medium text-sm md:text-base text-gray-900 truncate">{{ $project->name }}</h4>
                                        <p class="text-xs md:text-sm text-gray-600 truncate">PM: {{ $project->projectManager->name }}</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2 md:px-3 py-1 rounded-full text-xs font-medium 
                                        @if($project->status == 'completed') bg-green-100 text-green-800
                                        @elseif($project->status == 'in_progress') bg-yellow-100 text-yellow-800
                                        @elseif($project->status == 'planning') bg-blue-100 text-blue-800
                                        @elseif($project->status == 'on_hold') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $project->status_label }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-10 w-10 md:h-12 md:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada project</h3>
                        <p class="mt-1 text-xs md:text-sm text-gray-500">Anda belum ditugaskan ke project manapun.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Activity Summary -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
            <h3 class="text-base md:text-lg font-medium text-gray-900">Aktivitas Bulan Ini</h3>
        </div>
        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                <div class="text-center p-4 md:p-6 bg-blue-50 rounded-lg">
                    <div class="text-2xl md:text-3xl font-bold text-blue-600">{{ $activity_summary['joined_projects_this_month'] ?? 0 }}</div>
                    <div class="text-xs md:text-sm text-blue-600 mt-1">Project Baru Diikuti</div>
                    <p class="text-xs text-gray-500 mt-2">Project yang Anda ikuti bulan ini</p>
                </div>
                <div class="text-center p-4 md:p-6 bg-green-50 rounded-lg">
                    <div class="text-2xl md:text-3xl font-bold text-green-600">{{ $activity_summary['completed_projects_this_month'] ?? 0 }}</div>
                    <div class="text-xs md:text-sm text-green-600 mt-1">Project Diselesaikan</div>
                    <p class="text-xs text-gray-500 mt-2">Project yang berhasil diselesaikan</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection