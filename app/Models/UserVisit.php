<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVisit extends Model
{
    protected $table = 'user_visits';

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'visited_at',
    ];

    // Vì bảng không có created_at / updated_at
    public $timestamps = false;

    // -----------------------------
    // Thiết bị lấy từ user_agent
    // -----------------------------
    public function getDeviceAttribute()
    {
        $ua = $this->user_agent;

        // ƯU TIÊN MOBILE TRƯỚC
        if (stripos($ua, 'iPhone') !== false) return 'iPhone';
        if (stripos($ua, 'Android') !== false) return 'Android';
        if (stripos($ua, 'Mobile') !== false) return 'Mobile';

        // DESKTOP
        if (stripos($ua, 'Windows') !== false) return 'Windows';
        if (stripos($ua, 'Mac') !== false) return 'MacOS';

        // BROWSER
        if (stripos($ua, 'Chrome') !== false) return 'Chrome';
        if (stripos($ua, 'Firefox') !== false) return 'Firefox';
        if (stripos($ua, 'Safari') !== false) return 'Safari';

        return 'Other';
    }
}
