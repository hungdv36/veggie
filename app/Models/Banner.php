<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
  protected $fillable = [
    'title',
    'image',
    'url',
    'content',
    'sort_order',
    'status',
];

}
