@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
    <style>
        /* ==== RESET ==== */
        html,
        body {
            height: 100%;
            background-color: #f5f6fa;
            overflow-x: hidden;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE, Edge */
        }

        /* Ẩn thanh cuộn (Webkit) */
        ::-webkit-scrollbar {
            width: 0;
            height: 0;
        }

        /* ==== THẺ TỔNG QUAN ==== */
        .stat-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07);
            padding: 25px 20px;
            text-align: center;
            transition: 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 28px;
            color: #fff;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }

        .stat-label {
            color: #6c757d;
            font-size: 15px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
        }

        /* ==== PANEL CHUNG ==== */
        .x_panel {
            background: #fff;
            border-radius: 18px;
            border: none;
            padding: 20px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.06);
        }

        .x_title {
            border-bottom: 2px solid #eee;
            margin-bottom: 15px;
            padding-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .x_title h2 {
            font-size: 18px;
            font-weight: 600;
            color: #2d3436;
            margin: 0;
        }

        /* ==== BIỂU ĐỒ ==== */
        .chart-container {
            position: relative;
            height: 350px;
        }

        /* ==== BẢNG ==== */
        .table thead th {
            background: #f8f9fa;
            color: #555;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody tr:hover {
            background-color: #f3f6ff;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        /* ==== DANH SÁCH TOP SẢN PHẨM ==== */
        .list-group-item {
            border: none;
            border-radius: 10px !important;
            margin-bottom: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .list-group-item:hover {
            transform: translateX(4px);
            background: #f8faff;
        }

        .badge {
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 10px;
        }

        /* ==== CUỘN SIDEBAR ==== */
        .nav.side-menu {
            height: calc(100vh - 60px);
            overflow-y: auto;
            scrollbar-width: none;
        }

        .nav.side-menu::-webkit-scrollbar {
            display: none;
        }

        /* ==== RESPONSIVE ==== */
        @media (max-width: 992px) {
            .right_col {
                margin-left: 0;
                padding: 20px;
            }

            .left_col {
                position: relative;
                width: 100%;
                height: auto;
            }
        }
    </style>


    <div class="right_col" role="main">
        {{-- ==== DANH SÁCH CẦN LÀM ==== --}}
        <div class="x_panel mb-4">
            <div class="x_title">
                <h2><i class="fa fa-tasks text-primary me-2"></i> Danh sách cần làm</h2>
            </div>

            {{-- ==== Bộ lọc thời gian ==== --}}
            <form method="GET" class="row mb-3">
                <div class="col-md-3">
                    <select name="range" class="form-control" onchange="this.form.submit()">
                        <option value="today" {{ $range == 'today' ? 'selected' : '' }}>Hôm nay</option>
                        <option value="7days" {{ $range == '7days' ? 'selected' : '' }}>7 ngày gần nhất</option>
                        <option value="month" {{ $range == 'month' ? 'selected' : '' }}>Tháng này</option>
                        <option value="custom" {{ $range == 'custom' ? 'selected' : '' }}>Tùy chọn</option>
                    </select>
                </div>

                @if ($range == 'custom')
                    <div class="col-md-3">
                        <input type="date" name="start" value="{{ $startDate->toDateString() }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="end" value="{{ $endDate->toDateString() }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">Lọc</button>
                    </div>
                @endif
            </form>

            {{-- ==== Danh sách cần làm giống Shopee ==== --}}
            <div class="row text-center">

                @php
                    $items = [
                        'Chờ xác nhận' => $todo['pending'],
                        'Chờ lấy hàng' => $todo['processing'],
                        'Đã xử lý' => $todo['completed'],
                        'Đơn hủy' => $todo['cancelled'],
                    ];
                @endphp

                @foreach ($items as $label => $count)
                    <div class="col-md-3 col-6 mb-3">
                        <div
                            style="
                    background:#fff;
                    padding:18px;
                    border-radius:15px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.08);
                    transition:.3s;
                ">
                            <div style="font-size:14px;color:#666;">{{ $label }}</div>
                            <div style="font-size:26px;font-weight:700;color:#333;">{{ $count }}</div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        {{-- ==== THỐNG KÊ TỔNG ==== --}}
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4e73df, #224abe)">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="stat-label">Tổng người dùng</div>
                    <div class="stat-value text-primary">{{ $totalUsers }}</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #1cc88a, #13855c)">
                        <i class="fa fa-cube"></i>
                    </div>
                    <div class="stat-label">Tổng sản phẩm</div>
                    <div class="stat-value text-success">{{ $totalProducts }}</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f6c23e, #dda20a)">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="stat-label">Tổng đơn hàng</div>
                    <div class="stat-value text-warning">{{ $totalOrders }}</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #e74a3b, #be2617)">
                        <i class="fa fa-money"></i>
                    </div>
                    <div class="stat-label">Tổng doanh thu</div>
                    <div class="stat-value text-danger">{{ number_format($totalRevenue, 0, ',', '.') }} đ</div>
                </div>
            </div>
        </div>

        {{-- ==== BIỂU ĐỒ ==== --}}
        <div class="row">
            <div class="col-md-8">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-line-chart me-1 text-primary"></i> Doanh thu 7 ngày gần nhất</h2>
                    </div>
                    <div class="x_content">
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-pie-chart me-1 text-success"></i> Sản phẩm theo danh mục</h2>
                    </div>
                    <div class="x_content">
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-mobile text-info me-1"></i> Thiết bị truy cập</h2>
                </div>
                <div class="x_content">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="deviceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>



        {{-- ==== THỐNG KÊ ĐƠN HÀNG & TOP SẢN PHẨM ==== --}}
        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-list me-1 text-info"></i> Thống kê đơn hàng</h2>
                    </div>
                    <div class="x_content">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Trạng thái</th>
                                    <th class="text-end">Số lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderStats as $stat)
                                    <tr>
                                        <td>{{ ucfirst($stat->status) }}</td>
                                        <td class="text-end fw-bold">{{ $stat->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-star me-1 text-warning"></i> Top 5 sản phẩm bán chạy</h2>
                    </div>
                    <div class="x_content">
                        <ul class="list-group">
                            @foreach ($topProducts as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div><i class="fa fa-tag text-primary me-2"></i>{{ $item->name }}</div>
                                    <span class="badge bg-gradient-success">{{ $item->sold }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==== CHART.JS ==== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        /* ----------- Biểu đồ thiết bị truy cập ----------- */
        const deviceCtx = document.getElementById('deviceChart').getContext('2d');
        new Chart(deviceCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($deviceStats->keys()) !!},
                datasets: [{
                    data: {!! json_encode($deviceStats->values()) !!},
                    backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b'],
                    hoverOffset: 10
                }]
            },
            options: {
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 14,
                            padding: 15
                        }
                    }
                }
            }
        });

        /* ----------- Doanh thu 7 ngày gần nhất ----------- */
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($revenueChart->pluck('date')) !!},
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: {!! json_encode($revenueChart->pluck('total')) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1.5,
                    borderRadius: 10
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => v.toLocaleString() + ' đ'
                        }
                    }
                }
            }
        });

        /* ----------- Biểu đồ tròn danh mục sản phẩm ----------- */
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($productByCategory->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($productByCategory->pluck('total')) !!},
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#fd7e14',
                        '#20c997'
                    ],
                    hoverOffset: 10
                }]
            },
            options: {
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 14,
                            padding: 15
                        }
                    }
                }
            }
        });
    </script>
@endsection
