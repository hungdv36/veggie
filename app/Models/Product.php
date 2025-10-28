<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    //use SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'image',
        'brand',
        'category_id',
        'description',
        'price',
        'stock',
        'status',
        'unit'
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'status' => 'boolean',
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

    // Quan hệ với nhiều ảnh sản phẩm
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    // Helper: kiểm tra product có biến thể không
    public function hasVariants(): bool
    {
        return $this->variants()->exists();
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    // app/Models/Product.php
    public function getTotalStockAttribute()
    {
        if ($this->variants->count()) {
            return $this->variants->sum('quantity');
        }
        return $this->stock;
    }

    public function getStockStatusAttribute()
    {
        return $this->total_stock > 0 ? 'Còn hàng' : 'Hết hàng';
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
