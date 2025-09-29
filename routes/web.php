<?php
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProjectController; // UNIFIED CONTROLLER
use App\Http\Controllers\ProjectManager\DashboardController as PMDashboardController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Force logout untuk development
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

// ==========================
// ADMIN ROUTES
// ==========================
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // User Management
    Route::resource('users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    // Project Management (UNIFIED)
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

// ==========================
// PROJECT MANAGER ROUTES
// ==========================
Route::middleware(['auth', 'verified', 'role:project_manager'])->prefix('project-manager')->group(function () {
    Route::get('/dashboard', [PMDashboardController::class, 'index'])->name('pm.dashboard');
    
    // Project Management (UNIFIED)
    Route::resource('projects', ProjectController::class)->names([
        'index' => 'pm.projects.index',
        'create' => 'pm.projects.create',
        'store' => 'pm.projects.store',
        'show' => 'pm.projects.show',
        'edit' => 'pm.projects.edit',
        'update' => 'pm.projects.update',
        'destroy' => 'pm.projects.destroy',
    ]);
});

// ==========================
// MEMBER ROUTES
// ==========================
Route::middleware(['auth', 'verified', 'role:member'])->prefix('member')->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('member.dashboard');
    
    // Project View (UNIFIED - Read Only)
    Route::get('/projects', [ProjectController::class, 'index'])->name('member.projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('member.projects.show');
    
    // Status Update (Member Only)
    Route::patch('/projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('member.projects.status');
});

require __DIR__.'/auth.php';