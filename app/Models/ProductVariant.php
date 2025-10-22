<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variants';
    protected $fillable = ['product_id', 'size_id', 'color_id', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class,'size_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class,'color_id');
    }
}
