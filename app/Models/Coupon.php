<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'min_order',
        'usage_limit',
        'used',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // Kiểm tra coupon còn hạn và chưa vượt giới hạn
    public function isValid(): bool
    {
        $now = now();
        if (!$this->status) return false;
        if ($this->start_date && $now < $this->start_date) return false;
        if ($this->end_date && $now > $this->end_date) return false;
        if ($this->usage_limit !== null && $this->used >= $this->usage_limit) return false;
        return true;
    }
    // Tính giảm giá
    public function getDiscountAmount($orderTotal)
    {
        if (!$this->isValid()) return 0;

        return $this->type === 'percent'
            ? round($orderTotal * $this->value / 100, 0)
            : min($this->value, $orderTotal);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_coupons')
            ->withPivot('discount_amount')
            ->withTimestamps();
    }
}
