@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail User</h1>
                <p class="text-gray-600">Informasi lengkap user {{ $user->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Kembali
                </a>
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Edit User
                </a>
            </div>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-indigo-500">
            <div class="flex items-center text-white">
                <div class="h-16 w-16 rounded-full bg-white bg-opacity-20 flex items-center justify-center mr-4">
                    <span class="text-xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                    <p>{{ $user->email }}</p>
                    <p class="text-sm">{{ $user->role->display_name }}</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Role</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $user->role->display_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="text-sm mt-1">
                                <span class="px-2 py-1 text-xs rounded {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Account Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Akun</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bergabung</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $user->created_at->format('d F Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Terakhir Update</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $user->updated_at->format('d F Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection