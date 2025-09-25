<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
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
}
