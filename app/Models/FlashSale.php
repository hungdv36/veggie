<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $fillable = ['name', 'start_time', 'end_time', 'status'];
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(FlashSaleItem::class);
    }

    public function isActive()
    {
        $now = now();
        return $this->status == 1 && $this->start_time <= $now && $this->end_time >= $now;
    }
}
