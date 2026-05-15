<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role_id',
        'jenjang',
        'has_selected_jenjang',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the calon santri data for the user.
     */
    public function calonSantri()
    {
        return $this->hasOne(\App\Models\CalonSantri::class, 'no_telp', 'phone');
    }

    /**
     * Get user's role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Get all permissions for this user through their role
     */
    public function permissions()
    {
        return $this->role()->first()?->permissions() ?? collect();
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permissionName): bool
    {
        $role = $this->role;
        
        if (!$role) {
            return false;
        }

        // Admin has all permissions
        if ($role->name === 'admin') {
            return true;
        }

        return $role->hasPermission($permissionName);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissionNames): bool
    {
        $role = $this->role;
        
        if (!$role) {
            return false;
        }

        if ($role->name === 'admin') {
            return true;
        }

        return $role->hasAnyPermission($permissionNames);
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissionNames): bool
    {
        $role = $this->role;
        
        if (!$role) {
            return false;
        }

        if ($role->name === 'admin') {
            return true;
        }

        return $role->hasAllPermissions($permissionNames);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $roleName): bool
    {
        $role = $this->role;
        return $role && $role->name === $roleName;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roleNames): bool
    {
        $role = $this->role;
        return $role && in_array($role->name, $roleNames);
    }
}
