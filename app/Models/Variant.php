<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variant extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Table (mặc định là 'variants' nên khai báo tùy chọn).
     *
     * @var string
     */
    protected $table = 'variants';

    /**
     * Các trường có thể gán hàng loạt.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'color',
        'size',
        'price',
        'stock',
        'image',
    ];

    /**
     * Casts
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
    ];

    /**
     * Quan hệ về Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Kiểm tra còn hàng
     *
     * @return bool
     */
    public function isInStock(): bool
    {
        return ($this->stock ?? 0) > 0;
    }
}