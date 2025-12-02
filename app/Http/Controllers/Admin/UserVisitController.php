<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserVisit;

class UserVisitController extends Controller
{
    public function index(Request $request)
    {
        // Lọc theo ngày
        $start = $request->start_date;
        $end   = $request->end_date;

        $query = UserVisit::query();

        if ($start && $end) {
            $query->whereBetween('visited_at', [
                $start . ' 00:00:00',
                $end . ' 23:59:59'
            ]);
        }

        $visits = $query->orderBy('visited_at', 'desc')->paginate(20);

        return view('admin.visits.index', compact('visits'));
    }
}
