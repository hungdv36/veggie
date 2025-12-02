<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingAddress extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'address',      // Số nhà, tên đường
        'province',     // Tỉnh / Thành phố
        'district',     // Quận / Huyện
        'ward',         // Phường / Xã
        'default'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Optionally: accessor để hiển thị tên đầy đủ nếu lưu code thay vì text
    public function getProvinceNameAttribute()
    {
        return $this->province; // hoặc tra cứu tên từ API nếu lưu code
    }

    public function getDistrictNameAttribute()
    {
        return $this->district;
    }

    public function getWardNameAttribute()
    {
        return $this->ward;
    }
}
