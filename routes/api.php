<?php
// routes/api.php

use App\Http\Controllers\Api\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Get authenticated user info
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'data' => $request->user()->load('role')
    ]);
});

// Public API (no authentication required)
Route::prefix('v1')->group(function () {
    
    // Health check endpoint
    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'message' => 'API is running',
            'timestamp' => now()->toIso8601String()
        ]);
    });
});

// Protected API Routes (requires authentication)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    
    // ==========================================
    // PROJECT ENDPOINTS (5 Main Endpoints)
    // ==========================================
    
    // 1. GET /api/v1/projects - List all projects
    Route::get('/projects', [ProjectController::class, 'index']);
    
    // 2. POST /api/v1/projects - Create new project
    Route::post('/projects', [ProjectController::class, 'store']);
    
    // 3. GET /api/v1/projects/{id} - Get single project
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    
    // 4. PUT /api/v1/projects/{id} - Update project
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    
    // 5. DELETE /api/v1/projects/{id} - Delete project
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
    
    // BONUS: Update project status
    Route::patch('/projects/{id}/status', [ProjectController::class, 'updateStatus']);
    
    // ==========================================
    // PROJECT STATISTICS (Bonus)
    // ==========================================
    Route::get('/projects/statistics/overview', function () {
        $stats = [
            'total_projects' => \App\Models\Project::count(),
            'active_projects' => \App\Models\Project::where('is_active', true)->count(),
            'completed_projects' => \App\Models\Project::where('status', 'completed')->count(),
            'in_progress_projects' => \App\Models\Project::where('status', 'in_progress')->count(),
            'planning_projects' => \App\Models\Project::where('status', 'planning')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    });
});

// Optional: User Management API (untuk Admin)
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('v1/admin')->group(function () {
    
    // User endpoints
    Route::get('/users', function () {
        $users = \App\Models\User::with('role')->paginate(15);
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    });
    
    Route::post('/users', function (Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);
        
        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'email_verified_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user->load('role')
        ], 201);
    });
});