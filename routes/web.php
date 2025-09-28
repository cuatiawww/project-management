<?php
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProjectController;
use Illuminate\Support\Facades\Route;

// Redirect root ke login jika belum login, ke dashboard jika sudah login
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        } elseif ($user->isProjectManager()) {
            return redirect('/project-manager/dashboard');
        } else {
            return redirect('/member/dashboard');
        }
    }
    
    return redirect('/login');
});

use Illuminate\Support\Facades\Auth;

if (app()->environment('local')) {
    Route::get('/force-logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');
    });
}


Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard route - redirect berdasarkan role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        } elseif ($user->isProjectManager()) {
            return redirect('/project-manager/dashboard');
        } else {
            return redirect('/member/dashboard');
        }
    })->name('dashboard');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // User Management Routes
    Route::resource('users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    // Project Management Routes
    Route::resource('projects', ProjectController::class)->names([
        'index' => 'admin.projects.index',
        'create' => 'admin.projects.create',
        'store' => 'admin.projects.store',
        'show' => 'admin.projects.show',
        'edit' => 'admin.projects.edit',
        'update' => 'admin.projects.update',
        'destroy' => 'admin.projects.destroy',
    ]);
});

// Project Manager Routes (untuk nanti)
Route::middleware(['auth', 'verified', 'role:project_manager'])->prefix('project-manager')->group(function () {
    Route::get('/dashboard', function () {
        return view('project-manager.dashboard');
    })->name('pm.dashboard');
});

// Member Routes (untuk nanti) 
Route::middleware(['auth', 'verified', 'role:member'])->prefix('member')->group(function () {
    Route::get('/dashboard', function () {
        return view('member.dashboard');
    })->name('member.dashboard');
});

require __DIR__.'/auth.php';