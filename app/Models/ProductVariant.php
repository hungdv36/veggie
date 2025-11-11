<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'price',
        'sale_price',
        'quantity',
    ];

    protected $casts = [
        'price' => 'float',
        'sale_price' => 'float',
        'quantity' => 'integer',
    ];

    /**
     * 🔗 Quan hệ với sản phẩm
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * 🔗 Quan hệ với kích cỡ
     */
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    /**
     * 🔗 Quan hệ với màu sắc
     */
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    /**
     * 💰 Giá hiển thị — ưu tiên giá khuyến mãi nếu có
     */
    public function getDisplayPriceAttribute()
    {
        return $this->sale_price > 0 ? $this->sale_price : $this->price;
    }

    /**
     * 📦 Kiểm tra biến thể còn hàng không
     */
    public function getInStockAttribute()
    {
        return $this->quantity > 0;
    }
}
