<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use HasFactory, Notifiable;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'phone_number',
        'avatar',
        'address',
        'role_id',
        'activation_token',
        'google_id'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(related: Role::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(related: Review::class);
    }

    public function shippingAddresses(): HasMany
    {
        return $this->hasMany(related: ShippingAddress::class);
    }

    // Check status
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }

    public function isDeleted(): bool
    {
        return $this->status === 'deleted';
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('storage/uploads/users/images.jpg');
    }
    public function hasRole($roleName): bool
    {
        // Kiểm tra xem user có role liên kết với tên role
        return optional($this->role)->name === $roleName;
    }
    public function hasAnyPermission(array $permissions)
    {
        $userPermissions = $this->permissions ?? []; // cột permissions kiểu JSON
        return count(array_intersect($permissions, $userPermissions)) > 0;
    }
}
