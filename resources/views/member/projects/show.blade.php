@extends('layouts.admin')

@section('title', 'Detail Project: ' . $project->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
                <p class="text-gray-600 mt-1">Detail project dan informasi tim</p>
            </div>
            <a href="{{ route('member.projects.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Main Project Card -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <!-- Status & Progress Header -->
        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-8 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $project->status_color }}">
                        {{ $project->status_label }}
                    </span>
                    @if(!$project->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            Non-aktif
                        </span>
                    @endif
                    @if($project->isOverdue())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path>
                            </svg>
                            Terlambat
                        </span>
                    @elseif($project->days_remaining !== null && $project->days_remaining <= 7 && $project->days_remaining > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path>
                            </svg>
                            {{ $project->days_remaining }} hari lagi
                        </span>
                    @endif
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-gray-900">{{ $project->progress }}%</div>
                    <div class="text-sm text-gray-600">Progress</div>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="w-full bg-white/60 rounded-full h-3 shadow-inner">
                <div class="h-3 rounded-full transition-all duration-700 {{ str_replace('bg-', 'bg-', $project->progress_color) }}" 
                     style="width: {{ $project->progress }}%"></div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-8">
            <!-- Project Info Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Project</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                </div>
            </div>

            <!-- Team Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tim Project</h3>
                
                <!-- Project Manager -->
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

                <!-- Team Members -->
                @if($project->members->count() > 0)
                    <div>
                        <label class="text-sm font-medium text-gray-500 mb-3 block">Anggota Tim ({{ $project->members->count() }})</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($project->members as $member)
                                <div class="flex items-center p-3 rounded-xl border border-gray-200 
                                    {{ $member->id === auth()->id() ? 'bg-green-50 border-green-200' : 'bg-gray-50' }}">
                                    <div class="flex-shrink-0 h-10 w-10 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-white">
                                            {{ substr($member->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 truncate">
                                            {{ $member->name }}
                                            @if($member->id === auth()->id())
                                                <span class="text-green-600 text-sm">(Saya)</span>
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">{{ $member->email }}</p>
                                    </div>
                                    @if($member->id === auth()->id())
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Anda
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h4 class="mt-2 text-sm font-medium text-gray-900">Belum ada anggota tim</h4>
                        <p class="mt-1 text-sm text-gray-500">Project ini hanya memiliki Project Manager</p>
                    </div>
                @endif
            </div>

            <!-- Task Section (Future Feature) -->
            <div class="mb-8">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-blue-900">Task Management</h3>
                            <p class="text-blue-700">Task assignment dan progress tracking akan tersedia dalam update selanjutnya.</p>
                            <button onclick="showTaskFeatureModal()" 
                                    class="mt-2 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                                Lihat Task (Coming Soon)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Status Update Section (For Members) -->
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

<!-- Status Update Modal -->
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
        </div>
    </div>
</div>

<!-- Task Feature Modal -->
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

<script>
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

// Close modal when clicking outside
document.getElementById('statusUpdateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStatusModal();
    }
});
function showTaskFeatureModal() {
    document.getElementById('taskFeatureModal').classList.remove('hidden');
}

function closeTaskFeatureModal() {
    document.getElementById('taskFeatureModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('taskFeatureModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTaskFeatureModal();
    }
});
</script>
@endsection