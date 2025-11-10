<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $casts = [
        'invoice_sent_at' => 'datetime',
    ];
    protected $fillable = ['user_id', 'total_amount', 'status', 'shipping_address_id', 'cancel_reason', 'invoice_sent', 'invoice_sent_at', 'order_code'];

    public function orderItems(): HasMany
    {
        return $this->hasMany(related: OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(related: ShippingAddress::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(related: Payment::class);
    }

    public function status_logs(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id');
    }
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'order_coupons')
            ->withPivot('discount_amount')
            ->withTimestamps();
    }
    public function orderCoupons()
    {
        return $this->hasMany(OrderCoupon::class, 'order_id');
    }
}
