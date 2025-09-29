@extends('layouts.admin')

@section('title', 'My Projects')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Projects</h1>
                <p class="text-gray-600">Project yang saya ikuti sebagai member</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('member.projects.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Cari Project</label>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="{{ request('search') }}"
                           placeholder="Nama project..."
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Filter Status</label>
                    <select name="status" 
                            id="status"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Semua Status</option>
                        <option value="planning" {{ request('status') == 'planning' ? 'selected' : '' }}>Perencanaan</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Sedang Berjalan</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>Ditunda</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('member.projects.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-25 transition ease-in-out duration-150">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Projects List -->
    @if($projects->count() > 0)
        <div class="space-y-4">
            @foreach($projects as $project)
                <a href="{{ route('member.projects.show', $project) }}" 
                   class="block bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <!-- Header Row -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $project->name }}</h3>
                                <p class="text-gray-600 text-sm line-clamp-2">
                                    {{ $project->description ?: 'Tidak ada deskripsi' }}
                                </p>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $project->status_color }}">
                                    {{ $project->status_label }}
                                </span>
                            </div>
                        </div>

                        <!-- Progress Section -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Progress</span>
                                <span class="text-sm font-bold" style="color: {{ $project->progress_color === 'bg-red-500' ? '#EF4444' : ($project->progress_color === 'bg-yellow-500' ? '#F59E0B' : '#10B981') }}">
                                    {{ $project->progress }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="h-3 rounded-full transition-all duration-500 {{ str_replace('bg-', 'bg-', $project->progress_color) }}" 
                                     style="width: {{ $project->progress }}%"></div>
                            </div>
                        </div>

                        <!-- Project Info Row -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <!-- Project Manager -->
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-indigo-500 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium text-white">
                                        {{ substr($project->projectManager->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $project->projectManager->name }}</p>
                                    <p class="text-xs text-gray-500">Project Manager</p>
                                </div>
                            </div>

                            <!-- Members Count -->
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $project->members->count() }} Members</p>
                                    <p class="text-xs text-gray-500">Tim Project</p>
                                </div>
                            </div>

                            <!-- Timeline -->
                            @if($project->start_date)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $project->start_date->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-500">
                                            @if($project->end_date)
                                                s/d {{ $project->end_date->format('d M Y') }}
                                            @else
                                                Tanggal mulai
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Status Indicator -->
                            <div class="flex items-center justify-end">
                                @if($project->isOverdue())
                                    <div class="flex items-center text-red-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        <span class="text-sm font-medium">Terlambat</span>
                                    </div>
                                @elseif($project->days_remaining !== null && $project->days_remaining <= 7 && $project->days_remaining > 0)
                                    <div class="flex items-center text-yellow-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        <span class="text-sm font-medium">{{ $project->days_remaining }} hari lagi</span>
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            On Track
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Hover indicator -->
                    <div class="bg-gray-50 px-6 py-2 border-t border-gray-200">
                        <p class="text-xs text-gray-500 text-center">Klik untuk melihat detail project</p>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $projects->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada project</h3>
            <p class="mt-1 text-sm text-gray-500">
                @if(request()->hasAny(['search', 'status']))
                    Tidak ada project yang sesuai dengan filter yang dipilih.
                @else
                    Anda belum ditugaskan ke project manapun.
                @endif
            </p>
            <div class="mt-6">
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('member.projects.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Reset Filter
                    </a>
                @else
                    <p class="text-sm text-gray-500">Hubungi Project Manager untuk ditugaskan ke project.</p>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection