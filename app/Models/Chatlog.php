<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chatlog extends Model
{
    protected $table = 'chat_logs';
    protected $fillable = ['user_id', 'session_id', 'message', 'reply'];
}
