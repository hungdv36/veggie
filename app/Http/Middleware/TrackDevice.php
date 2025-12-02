<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\Models\DeviceLog;

class TrackDevice
{
    public function handle($request, Closure $next)
    {
        $agent = new Agent();

        $device = 'Desktop';

        if ($agent->isMobile()) {
            $device = 'Mobile';
        } elseif ($agent->isTablet()) {
            $device = 'Tablet';
        }

        DeviceLog::create([
            'device' => $device,
        ]);

        return $next($request);
    }
}
