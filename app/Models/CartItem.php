<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'variant_id', 'quantity'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(related: Product::class);
    }
}
