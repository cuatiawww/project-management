@extends('layouts.admin')

@section('title', 'Detail Project: ' . $project->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $project->status_color }}">
                        {{ $project->status_label }}
                    </span>
                    @if(!$project->is_active)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Non-aktif
                        </span>
                    @endif
                </div>
                <p class="text-gray-600">Kelola detail project dan tim Anda</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('pm.projects.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('pm.projects.edit', $project) }}" 
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
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div class="bg-white shadow rounded-lg mb-6 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium text-gray-900">Progress Project</h2>
            <span class="text-2xl font-bold" style="color: {{ $project->progress_color === 'bg-red-500' ? '#EF4444' : ($project->progress_color === 'bg-yellow-500' ? '#F59E0B' : ($project->progress_color === 'bg-gray-500' ? '#6B7280' : '#10B981')) }}">
                {{ $project->progress }}%
            </span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
            <div class="h-4 rounded-full transition-all duration-500 {{ str_replace('bg-', 'bg-', $project->progress_color) }}" 
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Project Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Project</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    </dl>
                    @if($project->description)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $project->description }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Team Members -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Tim Project</h3>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $project->members->count() }} Anggota
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Members -->
                    @if($project->members->count() > 0)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Anggota Tim</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($project->members as $member)
                                    <div class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                        <div class="flex-shrink-0 h-10 w-10 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-sm font-medium text-white">
                                                {{ substr($member->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $member->name }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $member->email }}</p>
                                            <p class="text-xs text-gray-500">
                                                Bergabung: {{ $member->pivot->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->format('d M Y') : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h4 class="mt-2 text-sm font-medium text-gray-900">Belum ada anggota tim</h4>
                            <p class="mt-1 text-sm text-gray-500">Tambahkan member untuk memulai project.</p>
                            <div class="mt-4">
                                <a href="{{ route('pm.projects.edit', $project) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Tambah Member
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
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
                            <span class="text-sm font-medium text-gray-900">{{ $project->members->count() }}</span>
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
                    <a href="{{ route('pm.projects.edit', $project) }}" 
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
                    <a href="{{ route('pm.projects.index') }}" 
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
    </div>
</div>

<!-- Delete Confirmation Modal -->
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
                <form method="POST" action="{{ route('pm.projects.destroy', $project) }}" class="inline">
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

<script>
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
</script>
@endsection