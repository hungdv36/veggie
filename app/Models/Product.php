<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'category_id', 'description', 'price', 'stock', 'status', 'unit'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(related: Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(related: ProductImage::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(related: CartItem::class);
    }
    
    public function firstImage()
    {
        return $this->hasOne(ProductImage::class)->orderBy('id', 'ASC');
    }
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
}
