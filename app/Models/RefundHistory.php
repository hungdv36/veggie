<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundHistory extends Model
{
    protected $fillable = ['refund_id', 'admin_id', 'status', 'receipt', 'note'];

    public function refund()
    {
        return $this->belongsTo(Refund::class);
    }
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
