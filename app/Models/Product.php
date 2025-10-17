<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

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
        'unit',
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

    // Quan hệ với biến thể
    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    // Helper: kiểm tra product có biến thể không
    public function hasVariants(): bool
    {
        return $this->variants()->exists();
    }
}
