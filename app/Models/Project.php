<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
        'start_date',
        'end_date',
        'project_manager_id',
        'created_by',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function projectManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')
                    ->withPivot(['role', 'joined_at'])
                    ->withTimestamps();
    }

    // Helper methods
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'planning' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'on_hold' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'planning' => 'Perencanaan',
            'in_progress' => 'Sedang Berjalan',
            'completed' => 'Selesai',
            'on_hold' => 'Ditunda',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown'
        };
    }

    public function getProgressAttribute(): int
    {
        return match($this->status) {
            'planning' => 0,
            'in_progress' => 50,
            'completed' => 100,
            'on_hold' => 25,
            'cancelled' => 0,
            default => 0
        };
    }

    public function getProgressColorAttribute(): string
    {
        return match($this->status) {
            'planning' => 'bg-red-500',
            'in_progress' => 'bg-yellow-500',
            'completed' => 'bg-green-500',
            'on_hold' => 'bg-gray-500',
            'cancelled' => 'bg-red-500',
            default => 'bg-gray-500'
        };
    }

    public function isOverdue(): bool
    {
        return $this->end_date && $this->end_date->isPast() && $this->status !== 'completed';
    }

    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->end_date || $this->status === 'completed') {
            return null;
        }
        
        return now()->diffInDays($this->end_date, false);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForProjectManager($query, $userId)
    {
        return $query->where('project_manager_id', $userId);
    }

    public function scopeForMember($query, $userId)
    {
        return $query->whereHas('members', function($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
}