<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'bank_name',
        'account_number',
        'account_holder',
        'receipt'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function histories()
    {
        return $this->hasMany(RefundHistory::class);
    }
}
