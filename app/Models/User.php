<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, FilamentUser
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
     public function canAccessPanel(Panel $panel): bool
    {
        // Optional: restrict access by role
        // return $this->role_id === 1; // or just return true;
        return auth()->user()?->role_id === 1;
    }
}
