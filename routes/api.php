<?php
// routes/api.php

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Health Check (Public)
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is running',
        'timestamp' => now()->toIso8601String(),
        'version' => 'v1'
    ]);
});

// ==========================================
// PUBLIC API (No Authentication)
// ==========================================
Route::prefix('v1')->group(function () {
    
    // Authentication Endpoints
    Route::post('/login', [AuthController::class, 'login']);
});

// ==========================================
// PROTECTED API (Requires Authentication)
// ==========================================
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    
    // User Info & Logout
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // ==========================================
    // PROJECT ENDPOINTS (5 Main Endpoints) ✅
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
    
    // ==========================================
    // BONUS ENDPOINTS
    // ==========================================
    
    // Update project status (for members)
    Route::patch('/projects/{id}/status', [ProjectController::class, 'updateStatus']);
    
    // Get project statistics
    Route::get('/statistics/projects', [ProjectController::class, 'statistics']);
});