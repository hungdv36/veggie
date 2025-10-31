<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Review extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['user_id','role_id', 'product_id', 'rating', 'comment'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(related: Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }
}
