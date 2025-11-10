<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCoupon extends Model
{
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
