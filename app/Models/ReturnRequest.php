<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    protected $fillable = [
        'order_id',
        'order_item_id',
        'user_id',
        'reason',
        'images',
        'videos',
        'status',
        'reject_reason',
        'staff_note'
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
