@extends('layouts.admin')

@section('title', 'Detail Project: ' . $project->name)

@section('content')
<div class="max-w-{{ auth()->user()->isMember() ? '5xl' : '6xl' }} mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-{{ auth()->user()->isMember() ? '6' : '8' }}">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $project->status_color }}">
                        {{ $project->status_label }}
                    </span>
                    @if(!$project->is_active && !auth()->user()->isMember())
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Non-aktif
                        </span>
                    @endif
                </div>
                <p class="text-gray-600">
                    @if(auth()->user()->isMember())
                        Detail project dan informasi tim
                    @else
                        Kelola detail project dan tim {{ auth()->user()->isProjectManager() ? 'Anda' : '' }}
                    @endif
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.projects.index') : (auth()->user()->isProjectManager() ? route('pm.projects.index') : route('member.projects.index')) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                
                @if(!auth()->user()->isMember())
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.projects.edit', $project) : route('pm.projects.edit', $project) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Project
                    </a>
                    <button type="button" 
                            onclick="confirmDelete()"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus Project
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div class="bg-white shadow{{ auth()->user()->isMember() ? '-sm' : '' }} rounded-{{ auth()->user()->isMember() ? 'xl' : 'lg' }} mb-6 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium text-gray-900">Progress Project</h2>
            <span class="text-2xl font-bold" style="color: {{ $project->progress_color === 'bg-red-500' ? '#EF4444' : ($project->progress_color === 'bg-yellow-500' ? '#F59E0B' : ($project->progress_color === 'bg-gray-500' ? '#6B7280' : '#10B981')) }}">
                {{ $project->progress }}%
            </span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-{{ auth()->user()->isMember() ? '3' : '4' }} mb-4">
            <div class="h-{{ auth()->user()->isMember() ? '3' : '4' }} rounded-full transition-all duration-{{ auth()->user()->isMember() ? '700' : '500' }} {{ str_replace('bg-', 'bg-', $project->progress_color) }}" 
                 style="width: {{ $project->progress }}%"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div class="p-3 {{ $project->status === 'planning' ? 'bg-blue-50 border border-blue-200' : 'bg-gray-50' }} rounded-lg">
                <div class="text-sm font-medium text-gray-900">Perencanaan</div>
                <div class="text-xs text-gray-500">0%</div>
            </div>
            <div class="p-3 {{ $project->status === 'in_progress' ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50' }} rounded-lg">
                <div class="text-sm font-medium text-gray-900">Sedang Berjalan</div>
                <div class="text-xs text-gray-500">50%</div>
            </div>
            <div class="p-3 {{ $project->status === 'on_hold' ? 'bg-gray-100 border border-gray-300' : 'bg-gray-50' }} rounded-lg">
                <div class="text-sm font-medium text-gray-900">Ditunda</div>
                <div class="text-xs text-gray-500">25%</div>
            </div>
            <div class="p-3 {{ $project->status === 'completed' ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }} rounded-lg">
                <div class="text-sm font-medium text-gray-900">Selesai</div>
                <div class="text-xs text-gray-500">100%</div>
            </div>
        </div>
        @if($project->isOverdue())
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <span class="text-sm font-medium text-red-800">Project terlambat dari target deadline!</span>
                </div>
            </div>
        @elseif($project->days_remaining !== null && $project->days_remaining <= 7 && $project->days_remaining > 0)
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <span class="text-sm font-medium text-yellow-800">Perhatian: Tersisa {{ $project->days_remaining }} hari menuju deadline!</span>
                </div>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-{{ auth()->user()->isMember() ? '1' : '3' }} gap-6">
        <!-- Project Information -->
        <div class="lg:col-span-{{ auth()->user()->isMember() ? '1' : '2' }} space-y-6">
            <!-- Basic Info -->
            <div class="bg-white shadow{{ auth()->user()->isMember() ? '-sm' : '' }} rounded-{{ auth()->user()->isMember() ? 'xl' : 'lg' }}">
                <div class="px-{{ auth()->user()->isMember() ? '8' : '6' }} py-{{ auth()->user()->isMember() ? '6' : '4' }} border-b border-gray-200">
                    <h3 class="text-lg font-{{ auth()->user()->isMember() ? 'semibold' : 'medium' }} text-gray-900">Informasi Project</h3>
                </div>
                <div class="p-{{ auth()->user()->isMember() ? '8' : '6' }}">
                    <dl class="grid grid-cols-1 md:grid-cols-{{ auth()->user()->isMember() ? '3' : '2' }} gap-6">
                        @if(auth()->user()->isMember())
                            <div class="space-y-4">
                                @if($project->description)
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Deskripsi</label>
                                        <p class="text-gray-900 mt-1">{{ $project->description }}</p>
                                    </div>
                                @endif
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Dibuat Oleh</label>
                                    <p class="text-gray-900 mt-1">{{ $project->creator->name }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                @if($project->start_date)
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Tanggal Mulai</label>
                                        <p class="text-gray-900 mt-1">{{ $project->start_date->format('d M Y') }}</p>
                                    </div>
                                @endif
                                @if($project->end_date)
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Target Selesai</label>
                                        <p class="text-gray-900 mt-1">{{ $project->end_date->format('d M Y') }}</p>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Total Anggota</label>
                                    <p class="text-gray-900 mt-1">{{ $project->members->count() + 1 }} orang</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Dibuat Pada</label>
                                    <p class="text-gray-900 mt-1">{{ $project->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        @else
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Project</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $project->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $project->status_color }}">
                                        {{ $project->status_label }}
                                    </span>
                                </dd>
                            </div>
                            @if($project->start_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Mulai</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $project->start_date->format('d M Y') }}</dd>
                                </div>
                            @endif
                            @if($project->end_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Target Selesai</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $project->end_date->format('d M Y') }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dibuat Oleh</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $project->creator->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dibuat Pada</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $project->created_at->format('d M Y H:i') }}</dd>
                            </div>
                        @endif
                    </dl>
                    @if($project->description && !auth()->user()->isMember())
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $project->description }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Team Members -->
            <div class="bg-white shadow{{ auth()->user()->isMember() ? '-sm' : '' }} rounded-{{ auth()->user()->isMember() ? 'xl' : 'lg' }}">
                <div class="px-{{ auth()->user()->isMember() ? '8' : '6' }} py-{{ auth()->user()->isMember() ? '6' : '4' }} border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-{{ auth()->user()->isMember() ? 'semibold' : 'medium' }} text-gray-900">Tim Project</h3>
                        @if(!auth()->user()->isMember())
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $project->members->count() . (auth()->user()->isProjectManager() ? '' : ' + 1') }} Anggota
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-{{ auth()->user()->isMember() ? '8' : '6' }}">
                    @if(!auth()->user()->isMember())
                        <!-- Project Manager (Admin & PM only) -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Project Manager</h4>
                            <div class="flex items-center p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                                <div class="flex-shrink-0 h-12 w-12 bg-indigo-500 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-lg font-medium text-white">
                                        {{ substr($project->projectManager->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        @if(auth()->user()->isProjectManager() && $project->project_manager_id === auth()->id())
                                            Anda
                                        @else
                                            {{ $project->projectManager->name }}
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $project->projectManager->email }}</p>
                                    <p class="text-xs text-indigo-600 font-medium">{{ $project->projectManager->role->display_name }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        Lead
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Project Manager (Member view) -->
                        <div class="mb-6">
                            <label class="text-sm font-medium text-gray-500 mb-3 block">Project Manager</label>
                            <div class="flex items-center p-4 bg-indigo-50 border border-indigo-200 rounded-xl">
                                <div class="flex-shrink-0 h-12 w-12 bg-indigo-500 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-lg font-medium text-white">
                                        {{ substr($project->projectManager->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $project->projectManager->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $project->projectManager->email }}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $project->projectManager->role->display_name }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Members -->
                    @if($project->members->count() > 0)
                        <div>
                            <{{ auth()->user()->isMember() ? 'label' : 'h4' }} class="text-sm font-medium text-gray-{{ auth()->user()->isMember() ? '500' : '700' }} mb-3 {{ auth()->user()->isMember() ? 'block' : '' }}">Anggota Tim{{ auth()->user()->isMember() ? ' (' . $project->members->count() . ')' : '' }}</{{ auth()->user()->isMember() ? 'label' : 'h4' }}>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($project->members as $member)
                                    <div class="flex items-center p-3 {{ auth()->user()->isMember() ? 'rounded-xl border border-gray-200' : 'bg-gray-50 border border-gray-200 rounded-lg' }} {{ auth()->user()->isMember() && $member->id === auth()->id() ? 'bg-green-50 border-green-200' : (auth()->user()->isMember() ? 'bg-gray-50' : '') }}">
                                        <div class="flex-shrink-0 h-10 w-10 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-sm font-medium text-white">
                                                {{ substr($member->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $member->name }}
                                                @if(auth()->user()->isMember() && $member->id === auth()->id())
                                                    <span class="text-green-600 text-sm">(Saya)</span>
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500 truncate">{{ $member->email }}</p>
                                            @if(!auth()->user()->isMember())
                                                <p class="text-xs text-gray-500">
                                                    Bergabung: {{ $member->pivot->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->format('d M Y') : 'N/A' }}
                                                </p>
                                            @endif
                                        </div>
                                        @if(auth()->user()->isMember() && $member->id === auth()->id())
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Anda
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-{{ auth()->user()->isMember() ? '8' : '6' }} {{ auth()->user()->isMember() ? 'bg-gray-50 rounded-xl border-2 border-dashed border-gray-300' : '' }}">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h4 class="mt-2 text-sm font-medium text-gray-900">Belum ada anggota tim</h4>
                            <p class="mt-1 text-sm text-gray-500">Project ini hanya memiliki Project Manager{{ auth()->user()->isMember() ? '' : '.' }}</p>
                            @if(auth()->user()->isProjectManager())
                                <div class="mt-4">
                                    <a href="{{ route('pm.projects.edit', $project) }}" 
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Tambah Member
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Member Status Update Section -->
            @if(auth()->user()->isMember())
                <div class="mb-8">
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-indigo-900">Update Status Project</h3>
                                    <p class="text-indigo-700">Status saat ini: <strong>{{ $project->status_label }}</strong></p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Status Update Buttons -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @if($project->status !== 'planning')
                            <button onclick="updateProjectStatus('planning')" 
                                    class="flex items-center justify-center px-4 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Perencanaan
                            </button>
                            @endif

                            @if($project->status !== 'in_progress')
                            <button onclick="updateProjectStatus('in_progress')" 
                                    class="flex items-center justify-center px-4 py-3 bg-yellow-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-yellow-700 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Mulai Kerja
                            </button>
                            @endif

                            @if($project->status !== 'completed' && in_array($project->status, ['in_progress', 'on_hold']))
                            <button onclick="updateProjectStatus('completed')" 
                                    class="flex items-center justify-center px-4 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-green-700 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Selesai
                            </button>
                            @endif

                            @if($project->status !== 'on_hold' && $project->status !== 'completed')
                            <button onclick="updateProjectStatus('on_hold')" 
                                    class="flex items-center justify-center px-4 py-3 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-700 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Tunda
                            </button>
                            @endif
                        </div>

                        @if($project->status === 'completed')
                        <div class="mt-4 p-3 bg-green-100 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-green-700 font-medium">Project sudah selesai! Terima kasih atas kontribusi Anda.</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Task Section (Future Feature) -->


                <!-- Quick Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $project->progress }}%</div>
                        <div class="text-sm text-blue-600">Progress Completion</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $project->members->count() + 1 }}</div>
                        <div class="text-sm text-green-600">Total Team Members</div>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-purple-600">
                            @if($project->days_remaining !== null)
                                @if($project->days_remaining > 0)
                                    {{ $project->days_remaining }}
                                @elseif($project->days_remaining < 0)
                                    {{ abs($project->days_remaining) }}
                                @else
                                    0
                                @endif
                            @else
                                -
                            @endif
                        </div>
                        <div class="text-sm text-purple-600">
                            @if($project->days_remaining !== null)
                                @if($project->days_remaining > 0)
                                    Hari Tersisa
                                @elseif($project->days_remaining < 0)
                                    Hari Terlambat
                                @else
                                    Deadline Hari Ini
                                @endif
                            @else
                                Timeline
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar (Admin & PM only) -->
        @if(!auth()->user()->isMember())
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Statistik</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Total Anggota</span>
                            <span class="text-sm font-medium text-gray-900">{{ $project->members->count() }}{{ auth()->user()->isProjectManager() ? '' : ' + 1' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Progress</span>
                            <span class="text-sm font-medium text-gray-900">{{ $project->progress }}%</span>
                        </div>
                        @if($project->start_date && $project->end_date)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Durasi Project</span>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $project->start_date->diffInDays($project->end_date) }} hari
                                </span>
                            </div>
                        @endif
                        @if($project->days_remaining !== null)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">
                                    @if($project->days_remaining > 0)
                                        Sisa Waktu
                                    @elseif($project->days_remaining < 0)
                                        Terlambat
                                    @else
                                        Deadline Hari Ini
                                    @endif
                                </span>
                                <span class="text-sm font-medium {{ $project->days_remaining <= 0 ? 'text-red-600' : ($project->days_remaining <= 7 ? 'text-yellow-600' : 'text-gray-900') }}">
                                    @if($project->days_remaining > 0)
                                        {{ $project->days_remaining }} hari
                                    @elseif($project->days_remaining < 0)
                                        {{ abs($project->days_remaining) }} hari
                                    @else
                                        Hari ini
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.projects.edit', $project) : route('pm.projects.edit', $project) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Project
                    </a>
                    <button type="button" 
                            onclick="confirmDelete()"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus Project
                    </button>
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.projects.index') : route('pm.projects.index') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Project Timeline Card -->
            @if($project->start_date || $project->end_date)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Timeline</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @if($project->start_date)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Mulai Project</p>
                                        <p class="text-xs text-gray-500">{{ $project->start_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @endif
                            @if($project->end_date)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 {{ $project->isOverdue() ? 'bg-red-100' : 'bg-green-100' }} rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 {{ $project->isOverdue() ? 'text-red-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Target Selesai</p>
                                        <p class="text-xs text-gray-500">{{ $project->end_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal (Admin & PM only) -->
@if(!auth()->user()->isMember())
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Hapus Project</h3>
            <p class="text-sm text-gray-500 mb-4">
                Apakah Anda yakin ingin menghapus project "<strong>{{ $project->name }}</strong>"? 
                Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait project ini.
            </p>
            <div class="flex justify-center space-x-3">
                <button type="button" 
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Batal
                </button>
                <form method="POST" action="{{ auth()->user()->isAdmin() ? route('admin.projects.destroy', $project) : route('pm.projects.destroy', $project) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Status Update Modal (Member only) -->
@if(auth()->user()->isMember())
<div id="statusUpdateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center mb-4">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-medium text-gray-900 text-center mb-4">Konfirmasi Update Status</h3>
            
            <form method="POST" action="{{ route('member.projects.status', $project) }}">
                @csrf
                @method('PATCH')
                
                <!-- Hidden status field -->
                <input type="hidden" name="status" id="newStatus" value="">
                
                <!-- Status Change Info -->
                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm">
                        <p><strong>Status saat ini:</strong> <span class="text-indigo-600">{{ $project->status_label }}</span></p>
                        <p><strong>Status baru:</strong> <span id="newStatusLabel" class="text-green-600"></span></p>
                    </div>
                </div>

                <!-- Notes (Optional) -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700">
                        Catatan (Opsional)
                    </label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="3"
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                              placeholder="Tambahkan catatan tentang perubahan status ini..."></textarea>
                    <p class="mt-1 text-xs text-gray-500">PM akan menerima notifikasi tentang perubahan status ini.</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeStatusModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Task Feature Modal (Member only) -->
@if(auth()->user()->isMember())
<div id="taskFeatureModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border-0 w-96 shadow-2xl rounded-3xl bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 mb-6 shadow-lg">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Task Management</h3>
            <div class="text-gray-600 mb-6 leading-relaxed">
                <p class="mb-3">🚀 <strong>Coming Soon!</strong></p>
                <p class="text-sm">Fitur Task Management sedang dalam pengembangan dan akan segera tersedia dengan fitur:</p>
                <ul class="text-left text-sm mt-3 space-y-1">
                    <li>• ✅ Task assignment dari PM</li>
                    <li>• 📊 Progress tracking real-time</li>
                    <li>• 💬 Kolaborasi tim</li>
                    <li>• 🔔 Notifikasi deadline</li>
                    <li>• 📈 Reporting otomatis</li>
                </ul>
            </div>
            <div class="flex justify-center">
                <button type="button" 
                        onclick="closeTaskFeatureModal()"
                        class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:from-blue-600 hover:to-purple-700 font-medium transform hover:scale-105 transition-all duration-300 shadow-lg">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<script>
@if(!auth()->user()->isMember())
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
@endif

@if(auth()->user()->isMember())
const statusLabels = {
    'planning': 'Perencanaan',
    'in_progress': 'Sedang Berjalan',
    'completed': 'Selesai', 
    'on_hold': 'Ditunda'
};

function updateProjectStatus(status) {
    document.getElementById('newStatus').value = status;
    document.getElementById('newStatusLabel').textContent = statusLabels[status];
    document.getElementById('statusUpdateModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusUpdateModal').classList.add('hidden');
}

function showTaskFeatureModal() {
    document.getElementById('taskFeatureModal').classList.remove('hidden');
}

function closeTaskFeatureModal() {
    document.getElementById('taskFeatureModal').classList.add('hidden');
}

// Close modals when clicking outside
document.getElementById('statusUpdateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStatusModal();
    }
});

document.getElementById('taskFeatureModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTaskFeatureModal();
    }
});
@endif
</script>
@endsection