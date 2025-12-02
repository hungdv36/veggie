<?php

namespace App\Http\MIddleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserVisit;

class LogUserVisit
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {

            $userId = Auth::id();

            // Check if user already visited today
            $existing = UserVisit::where('user_id', $userId)
                ->whereDate('visited_at', today())
                ->first();

            if (!$existing) {

                $ua = $request->header('User-Agent');

                if (str_contains($ua, 'android') || str_contains($ua, 'mobile') || str_contains($ua, 'iphone')) {
                    $device = 'Mobile';
                } elseif (str_contains($ua, 'ipad') || str_contains($ua, 'tablet')) {
                    $device = 'Tablet';
                } else {
                    $device = 'Desktop';
                }

                UserVisit::create([
                    'user_id'    => $userId,
                    'ip_address' => $request->ip(),
                    'user_agent' => $ua,
                    'device'     => $device,
                    'visited_at' => now(),
                ]);
            }
        }

        return $next($request);
    }
}
