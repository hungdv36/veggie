<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\DeviceLog;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    /* ========== BỘ LỌC THỜI GIAN ========== */
    $range = $request->input('range', '7days');
    $startDate = Carbon::now()->subDays(7);
    $endDate = Carbon::now();

    if ($range == 'today') {
        $startDate = Carbon::today();
    } elseif ($range == 'month') {
        $startDate = Carbon::now()->startOfMonth();
    } elseif ($range == 'custom') {
        $startDate = Carbon::parse($request->start);
        $endDate   = Carbon::parse($request->end);
    }

    /* ========== DANH SÁCH CẦN LÀM ========== */
    $todo = [
        'pending' => DB::table('orders')
            ->where('status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count(),

        'processing' => DB::table('orders')
            ->where('status', 'processing')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count(),

        'completed' => DB::table('orders')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count(),

        'cancelled' => DB::table('orders')
            ->where('status', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count(),
    ];

    /* ========== TỔNG NGƯỜI DÙNG (THEO LỌC) ========== */
    $totalUsers = DB::table('users')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

    /* ========== TỔNG SẢN PHẨM ========== */
    // Nếu bạn muốn tổng sản phẩm cố định, dùng dòng này:
    // $totalProducts = DB::table('products')->count();

    // Nếu bạn muốn lọc theo thời gian tạo:
    $totalProducts = DB::table('products')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

    /* ========== TỔNG ĐƠN HÀNG ========== */
    $totalOrders = DB::table('orders')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

    /* ========== DOANH THU ========== */
    $totalRevenue = DB::table('orders')
        ->where('status', 'completed')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->sum('total_amount');

    /* ========== ORDER STATS ========== */
    $orderStats = DB::table('orders')
        ->select('status', DB::raw('COUNT(*) as total'))
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('status')
        ->get();

    /* ========== TOP SẢN PHẨM ========== */
    $topProducts = DB::table('products')
        ->join('order_items', 'products.id', '=', 'order_items.product_id')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->whereBetween('orders.created_at', [$startDate, $endDate])
        ->where('orders.status', 'completed')
        ->select('products.name', DB::raw('SUM(order_items.quantity) as sold'))
        ->groupBy('products.id', 'products.name')
        ->orderByDesc('sold')
        ->limit(5)
        ->get();

    /* ========== BIỂU ĐỒ DOANH THU ========== */
    $revenueChart = DB::table('orders')
        ->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
        ->where('status', 'completed')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    /* ========== SẢN PHẨM THEO DANH MỤC ========== */
    $productByCategory = DB::table('categories')
        ->leftJoin('products', 'categories.id', '=', 'products.category_id')
        ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
        ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
        ->whereBetween('orders.created_at', [$startDate, $endDate])
        ->where('orders.status', 'completed')
        ->select('categories.name', DB::raw('COUNT(products.id) as total'))
        ->groupBy('categories.name')
        ->get();

    /* ========== THỐNG KÊ THIẾT BỊ TRUY CẬP ========== */
    $deviceStats = DeviceLog::whereBetween('created_at', [$startDate, $endDate])
        ->selectRaw('device, COUNT(*) as total')
        ->groupBy('device')
        ->pluck('total', 'device');

    /* RETURN VIEW */
    return view('admin.pages.dashboard', compact(
        'todo',
        'range',
        'startDate',
        'endDate',
        'deviceStats',
        'orderStats',
        'topProducts',
        'productByCategory',
        'revenueChart',
        'totalUsers',
        'totalProducts',
        'totalOrders',
        'totalRevenue'
    ));
}

    
}
