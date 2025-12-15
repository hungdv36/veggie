<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\UserVisit;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /* ================= RANGE ================= */
        $range = $request->get('range', '7days');
        $month = $request->get('month');
        $year  = $request->get('year', now()->year);

        switch ($range) {
            case 'today':
                $startDate = Carbon::today();
                $endDate   = Carbon::now();
                break;

            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate   = Carbon::now();
                break;

            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate   = Carbon::now();
                break;

            default: // 7days
                $startDate = Carbon::now()->subDays(6);
                $endDate   = Carbon::now();
        }

        /* ================= FILTER BY MONTH ================= */
        if ($month) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate   = Carbon::create($year, $month, 1)->endOfMonth();
        }

        /* ================= PREVIOUS PERIOD ================= */
        $days = $startDate->diffInDays($endDate) + 1;
        $prevStart = (clone $startDate)->subDays($days);
        $prevEnd   = (clone $startDate)->subDay();

        /* ================= TODO ================= */
        $todo = [
            'pending'    => DB::table('orders')->where('status', 'pending')->count(),
            'processing' => DB::table('orders')->where('status', 'processing')->count(),
            'completed'  => DB::table('orders')->where('status', 'completed')->count(),
            'cancelled'  => DB::table('orders')->where('status', 'cancelled')->count(),
        ];

        /* ================= TOTAL ================= */
        $totalUsers    = DB::table('users')->count();
        $totalOrders   = DB::table('orders')->count();
        $totalProducts = DB::table('products')->count();

        $totalRevenue = DB::table('orders')
            ->whereIn('status', ['completed', 'received'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        /* ================= PREVIOUS ================= */
        $prevRevenue = DB::table('orders')
            ->whereIn('status', ['completed', 'received'])
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->sum('total_amount');

        $prevOrders = DB::table('orders')
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->count();

        $prevUsers = DB::table('users')
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->count();

        /* ================= GROWTH (%) ================= */
        $revenueGrowth = $prevRevenue > 0
            ? (($totalRevenue - $prevRevenue) / $prevRevenue) * 100
            : 100;

        $orderGrowth = $prevOrders > 0
            ? (($totalOrders - $prevOrders) / $prevOrders) * 100
            : 100;

        $userGrowth = $prevUsers > 0
            ? (($totalUsers - $prevUsers) / $prevUsers) * 100
            : 100;

        /* ================= REVENUE CHART ================= */
        $revenueChart = DB::table('orders')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereIn('status', ['completed', 'received'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        /* ================= CATEGORY ================= */
        $productByCategory = DB::table('categories')
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->select(
                'categories.name',
                DB::raw('COUNT(products.id) as total')
            )
            ->groupBy('categories.name')
            ->get();

        /* ================= TOP PRODUCTS ================= */
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['completed', 'received'])
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        /* ================= ORDER STATUS CHART ================= */
        $orderStatusChart = DB::table('orders')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        /* ================= VISIT / DEVICE ================= */
        $totalVisits = UserVisit::whereBetween('visited_at', [$startDate, $endDate])->count();

        $deviceStats = UserVisit::whereBetween('visited_at', [$startDate, $endDate])
            ->select('device', DB::raw('COUNT(*) as total'))
            ->groupBy('device')
            ->pluck('total', 'device');

        return view('admin.pages.dashboard', compact(
            'range',
            'month',
            'year',
            'todo',
            'totalUsers',
            'totalOrders',
            'totalProducts',
            'totalRevenue',
            'revenueGrowth',
            'orderGrowth',
            'userGrowth',
            'revenueChart',
            'productByCategory',
            'topProducts',
            'orderStatusChart',
            'totalVisits',
            'deviceStats'
        ));
    }
}
