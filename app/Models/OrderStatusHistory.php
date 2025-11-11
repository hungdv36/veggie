<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStatusHistory extends Model
{

    protected $table = 'order_status_history';
    protected $fillable = [
        'order_id',
        'role_id',
        'old_status',
        'status',
        'changed_at',
        'notes'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
