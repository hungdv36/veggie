<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Review;
use App\Models\User;

class ReviewDeletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'admin_id',
        'reason',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id')->withTrashed();
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
