<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'price',
        'stock',
        'status',
        'unit'
    ];

    // Quan hệ với danh mục
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Quan hệ với CartItem
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // Lấy ảnh đầu tiên của sản phẩm
    public function firstImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->orderBy('id', 'ASC');
    }

    // Quan hệ với biến thể
    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }
}
