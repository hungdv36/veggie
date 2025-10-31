<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'variant_id'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(related: Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(related: Order::class);
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
