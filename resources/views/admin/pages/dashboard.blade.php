@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<style>
    body.dark-mode { background: #1e1e2f; color: #f1f1f1; }
    .dark-mode .x_panel, .dark-mode .stat-card { background: #2a2a40; color: #fff; }
    .stat-card { background: #fff; border-radius:16px; padding:20px; text-align:center; box-shadow:0 4px 14px rgba(0,0,0,.08); transition:.3s; }
    .stat-card:hover { transform:translateY(-5px); }
    .stat-icon { width:60px;height:60px;border-radius:50%;margin:auto;display:flex;align-items:center;justify-content:center;font-size:24px;color:#fff;margin-bottom:10px; }
    .growth-up { color: #1cc88a; font-weight: 600; }
    .growth-down { color: #e74a3b; font-weight: 600; }
    .x_panel { background: #fff; border-radius:16px; padding:20px; margin-bottom:25px; box-shadow:0 3px 12px rgba(0,0,0,.06); }
    .x_title { border-bottom:1px solid #eee; margin-bottom:15px; padding-bottom:8px; display:flex; justify-content:space-between; align-items:center; }
    .chart-container { height:320px; }
</style>

<div class="right_col" role="main">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📊 Dashboard</h2>
        <button onclick="toggleDarkMode()" class="btn btn-dark btn-sm">🌙 Dark Mode</button>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="row mb-4">
        <div class="col-md-3">
            <select name="range" class="form-control" onchange="this.form.submit()">
                <option value="today" {{ $range=='today'?'selected':'' }}>Hôm nay</option>
                <option value="7days" {{ $range=='7days'?'selected':'' }}>7 ngày</option>
                <option value="month" {{ $range=='month'?'selected':'' }}>Tháng</option>
                <option value="year" {{ $range=='year'?'selected':'' }}>Năm</option>
            </select>
        </div>

        <div class="col-md-3">
            <select name="month" class="form-control">
                @for($m=1;$m<=12;$m++)
                    <option value="{{ $m }}" {{ $month==$m?'selected':'' }}>Tháng {{ $m }}</option>
                @endfor
            </select>
        </div>

        <div class="col-md-3">
            <select name="year" class="form-control">
                @for($y=date('Y');$y>=date('Y')-5;$y--)
                    <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary">Lọc</button>
        </div>
    </form>

    {{-- TODO LIST --}}
    <div class="row mb-4">
        @php
            $todoList = [
                ['Chờ xác nhận', $todo['pending'], 'warning'],
                ['Chờ lấy hàng', $todo['processing'], 'info'],
                ['Đã xử lý', $todo['completed'], 'success'],
                ['Đơn hủy', $todo['cancelled'], 'danger']
            ];
        @endphp
        @foreach($todoList as $item)
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card">
                <div class="stat-label">{{ $item[0] }}</div>
                <div class="stat-value text-{{ $item[2] }}" style="font-size:28px;font-weight:700">{{ $item[1] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- KPI --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary"><i class="fa fa-users"></i></div>
                <div>Tổng người dùng</div>
                <h3>{{ $totalUsers }}</h3>
                <small class="{{ $userGrowth>=0?'growth-up':'growth-down' }}">{{ round($userGrowth,1) }}%</small>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning"><i class="fa fa-shopping-cart"></i></div>
                <div>Tổng đơn hàng</div>
                <h3>{{ $totalOrders }}</h3>
                <small class="{{ $orderGrowth>=0?'growth-up':'growth-down' }}">{{ round($orderGrowth,1) }}%</small>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon bg-success"><i class="fa fa-cube"></i></div>
                <div>Tổng sản phẩm</div>
                <h3>{{ $totalProducts }}</h3>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon bg-danger"><i class="fa fa-money"></i></div>
                <div>Doanh thu</div>
                <h3>{{ number_format($totalRevenue,0,',','.') }} đ</h3>
                <small class="{{ $revenueGrowth>=0?'growth-up':'growth-down' }}">{{ round($revenueGrowth,1) }}%</small>
            </div>
        </div>
    </div>

    {{-- BIỂU ĐỒ --}}
    <div class="row">
        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title"><h3>📈 Doanh thu theo thời gian</h3></div>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title"><h3>📦 Sản phẩm theo danh mục</h3></div>
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- TOP SẢN PHẨM --}}
    <div class="x_panel mt-4">
        <div class="x_title"><h3>🔥 Top sản phẩm bán chạy</h3></div>
        <table class="table">
            <thead>
                <tr><th>Sản phẩm</th><th>Đã bán</th></tr>
            </thead>
            <tbody>
                @foreach($topProducts as $item)
                    <tr><td>{{ $item->name }}</td><td>{{ $item->total_sold }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ORDER STATUS CHART --}}
    <div class="x_panel mt-4">
        <div class="x_title"><h3>📊 Trạng thái đơn hàng</h3></div>
        <canvas id="orderStatusChart"></canvas>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function toggleDarkMode() { document.body.classList.toggle('dark-mode'); }

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($revenueChart->pluck('date')) !!},
        datasets: [{ label:'Doanh thu', data:{!! json_encode($revenueChart->pluck('total')) !!}, borderColor:'#4e73df', backgroundColor:'rgba(78,115,223,0.15)', tension:.4, fill:true }]
    }
});

new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($productByCategory->pluck('name')) !!},
        datasets:[{ data:{!! json_encode($productByCategory->pluck('total')) !!}, backgroundColor:['#4e73df','#1cc88a','#f6c23e','#e74a3b','#36b9cc'] }]
    }
});

new Chart(document.getElementById('orderStatusChart'), {
    type: 'pie',
    data: {
        labels: {!! json_encode($orderStatusChart->pluck('status')) !!},
        datasets:[{ data:{!! json_encode($orderStatusChart->pluck('total')) !!}, backgroundColor:['#f6c23e','#36b9cc','#1cc88a','#e74a3b'] }]
    }
});
</script>
@endsection
