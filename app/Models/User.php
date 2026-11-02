<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'phone',
    'profile_photo',
    'role',
    'password',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function communityMemberships()
    {
        return $this->hasMany(CommunityMember::class);
    }

    public function ledCommunities()
    {
        return $this->hasMany(Community::class, 'leader_id');
    }

    public function communities()
    {
        return $this->belongsToMany(
            Community::class,
            'community_members',
            'user_id',
            'community_id'
        )->withPivot('status', 'joined_at')
         ->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
