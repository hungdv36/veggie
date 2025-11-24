<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\DeviceLog;


class DashboardController extends Controller
{
    public function index()
    {
        // Tổng số người dùng
        $totalUsers = DB::table('users')->count();

        // Tổng sản phẩm
        $totalProducts = DB::table('products')->count();

        // Tổng đơn hàng
        $totalOrders = DB::table('orders')->count();

        // Tổng doanh thu (chỉ tính đơn completed)
        $totalRevenue = DB::table('orders')
            ->where('status', 'completed')
            ->sum('total_amount');

        // Thống kê đơn hàng theo trạng thái
        $orderStats = DB::table('orders')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();
            $topProducts = DB::table('products')
    ->join('order_items', 'products.id', '=', 'order_items.product_id')
    ->select('products.name', DB::raw('SUM(order_items.quantity) as sold'))
    ->groupBy('products.id','products.name')
    ->orderByDesc('sold')
    ->limit(5)
    ->get();
    // ---- Biểu đồ doanh thu 7 ngày gần nhất ----
$revenueChart = DB::table('orders')
    ->select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('SUM(total_amount) as total')
    )
    ->where('status', 'completed')
    ->where('created_at', '>=', Carbon::now()->subDays(7))
    ->groupBy('date')
    ->orderBy('date')
    ->get();
    // Thống kê sản phẩm theo danh mục
$productByCategory = DB::table('categories')
    ->leftJoin('products', 'categories.id', '=', 'products.category_id')
    ->select('categories.name', DB::raw('COUNT(products.id) as total'))
    ->groupBy('categories.name')
    ->get();



        
        // ---- Thống kê thiết bị truy cập ----
        $deviceStats = DeviceLog::selectRaw('device, COUNT(*) as total')
           ->groupBy('device')
           ->pluck('total', 'device');

   return view('admin.pages.dashboard', compact(
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
    
        // Biểu đồ doanh thu 7 ngày gần nhất
        $revenueChart = DB::table('orders')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top 5 sản phẩm bán chạy
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('sold')
            ->limit(5)
            ->get();

        // Thống kê sản phẩm theo danh mục
        $productByCategory = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('COUNT(products.id) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->get();
            
        return view('admin.pages.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'orderStats',
            'revenueChart',
            'topProducts',
            'productByCategory'
        ));
    }
}
