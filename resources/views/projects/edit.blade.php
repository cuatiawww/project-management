@extends('layouts.admin')

@section('title', 'Edit Project: ' . $project->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Project</h1>
                <p class="text-gray-600">Update informasi project "{{ $project->name }}"</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.projects.show', $project) : route('pm.projects.show', $project) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Detail
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Update Informasi Project</h2>
            <p class="text-sm text-gray-600">Ubah data project sesuai kebutuhan</p>
        </div>
        
        <form method="POST" action="{{ auth()->user()->isAdmin() ? route('admin.projects.update', $project) : route('pm.projects.update', $project) }}" class="p-6">
            @csrf
            @method('PUT')

            <!-- Basic Project Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Project Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Nama Project <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $project->name) }}"
                           placeholder="Contoh: Sistem Manajemen Inventory"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-300 @enderror"
                           required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">
                        Tanggal Mulai
                    </label>
                    <input type="date" 
                           name="start_date" 
                           id="start_date" 
                           value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('start_date') border-red-300 @enderror">
                    @error('start_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">
                        Tanggal Selesai Target
                    </label>
                    <input type="date" 
                           name="end_date" 
                           id="end_date" 
                           value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('end_date') border-red-300 @enderror">
                    @error('end_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <label for="status" class="block text-sm font-medium text-gray-700">
                        Status Project <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            id="status"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('status') border-red-300 @enderror"
                            required>
                        <option value="planning" {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>Perencanaan</option>
                        <option value="in_progress" {{ old('status', $project->status) == 'in_progress' ? 'selected' : '' }}>Sedang Berjalan</option>
                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="on_hold" {{ old('status', $project->status) == 'on_hold' ? 'selected' : '' }}>Ditunda</option>
                        <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Progress otomatis berdasarkan status project</p>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700">
                    Deskripsi Project
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="4"
                          placeholder="Jelaskan tujuan, scope, dan detail project ini..."
                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-300 @enderror">{{ old('description', $project->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Project Manager Selection (Admin Only) -->
            @if(auth()->user()->isAdmin() && isset($projectManagers))
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Project Manager <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($projectManagers as $pm)
                        <div class="relative">
                            <input type="radio" 
                                   name="project_manager_id" 
                                   id="pm_{{ $pm->id }}" 
                                   value="{{ $pm->id }}"
                                   {{ old('project_manager_id', $project->project_manager_id) == $pm->id ? 'checked' : '' }}
                                   class="sr-only peer"
                                   required>
                            <label for="pm_{{ $pm->id }}" 
                                   class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition-colors">
                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-500 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-sm font-medium text-white">
                                        {{ substr($pm->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $pm->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $pm->email }}</p>
                                    <p class="text-xs text-gray-500">{{ $pm->role->display_name }}</p>
                                </div>
                                @if($pm->id == $project->project_manager_id)
                                    <div class="flex-shrink-0 ml-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Current
                                        </span>
                                    </div>
                                @endif
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('project_manager_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <!-- Team Members Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Team Members (Opsional)
                </label>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    @if($members->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-64 overflow-y-auto">
                            @foreach($members as $member)
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="members[]" 
                                           id="member_{{ $member->id }}" 
                                           value="{{ $member->id }}"
                                           {{ in_array($member->id, old('members', $project->members->pluck('id')->toArray())) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="member_{{ $member->id }}" class="ml-3 flex items-center cursor-pointer">
                                        <div class="flex-shrink-0 h-8 w-8 bg-purple-500 rounded-full flex items-center justify-center mr-2">
                                            <span class="text-xs font-medium text-white">
                                                {{ substr($member->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $member->email }}</p>
                                            @if($project->members->contains('id', $member->id))
                                                <p class="text-xs text-green-600 font-medium">Current member</p>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">
                            Belum ada user dengan role Member yang tersedia.
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.users.create') }}" class="text-indigo-600 hover:text-indigo-500">Buat user member dulu</a>
                            @endif
                        </p>
                    @endif
                </div>
                @error('members')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Member yang sudah ada akan tetap dipertahankan kecuali Anda hapus centangnya.</p>
            </div>

            <!-- Project Active Status -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Project</label>
                <div class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active" 
                           value="1"
                           {{ old('is_active', $project->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Project aktif
                    </label>
                </div>
                <p class="mt-1 text-sm text-gray-500">Project yang tidak aktif tidak akan muncul di dashboard member</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.projects.show', $project) : route('pm.projects.show', $project) }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-25 transition ease-in-out duration-150">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Project
                </button>
            </div>
        </form>
    </div>

    <!-- Current Project Info Card -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Informasi Project Saat Ini</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><strong>Status:</strong> {{ $project->status_label }}</p>
                                <p><strong>Progress:</strong> {{ $project->progress }}%</p>
                                <p><strong>Project Manager:</strong> 
                                    @if(auth()->user()->isProjectManager() && $project->project_manager_id === auth()->id())
                                        Anda
                                    @else
                                        {{ $project->projectManager->name }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p><strong>Anggota Tim:</strong> {{ $project->members->count() }} member</p>
                                <p><strong>Dibuat:</strong> {{ $project->created_at->format('d M Y') }}</p>
                                <p><strong>Status Aktif:</strong> {{ $project->is_active ? 'Aktif' : 'Non-aktif' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tips Card -->
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Tips Edit Project</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <ul class="space-y-1">
                        @if(auth()->user()->isAdmin())
                            <li>• Hati-hati mengubah Project Manager - pastikan koordinasi dengan tim</li>
                        @endif
                        <li>• Perubahan member akan langsung mempengaruhi akses mereka ke project</li>
                        <li>• Progress otomatis berubah sesuai status yang dipilih</li>
                        <li>• Project yang dinonaktifkan tidak akan terlihat di dashboard member</li>
                        <li>• Timeline dapat diubah sesuai kebutuhan project</li>
                        @if(auth()->user()->isProjectManager())
                            <li>• Informasikan perubahan penting ke tim Anda</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validation untuk tanggal
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = new Date(this.value);
    const endDateInput = document.getElementById('end_date');
    const endDate = new Date(endDateInput.value);
    
    if (endDateInput.value && startDate > endDate) {
        alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
        this.value = '';
    }
});

document.getElementById('end_date').addEventListener('change', function() {
    const endDate = new Date(this.value);
    const startDateInput = document.getElementById('start_date');
    const startDate = new Date(startDateInput.value);
    
    if (startDateInput.value && endDate < startDate) {
        alert('Tanggal selesai tidak boleh lebih kecil dari tanggal mulai');
        this.value = '';
    }
});
</script>
@endsection