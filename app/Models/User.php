<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable; 
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // ======================
    // Role Checking
    // ======================
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($role)
    {
        return $this->role->name === $role;
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isProjectManager()
    {
        return $this->hasRole('project_manager');
    }

    public function isMember()
    {
        return $this->hasRole('member');
    }

    // ======================
    // Relationships
    // ======================
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    public function memberProjects()
    {
        return $this->belongsToMany(Project::class, 'project_members')
                    ->withPivot(['role', 'joined_at'])
                    ->withTimestamps();
    }

    // ======================
    // Helpers
    // ======================
    public function canManageProjects(): bool
    {
        return $this->isAdmin() || $this->isProjectManager();
    }

    public function getProjectsAttribute()
    {
        if ($this->isAdmin()) {
            return Project::all();
        } elseif ($this->isProjectManager()) {
            return $this->managedProjects;
        } else {
            return $this->memberProjects;
        }
    }
}
