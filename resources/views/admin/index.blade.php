@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-8 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Welcome back, {{ $admin->name }}!</h5>
                                <p class="mb-4">
                                    Your platform has seen <span
                                        class="fw-bold">{{ number_format($revenuePercentChange, 1) }}%</span> more revenue
                                    this month compared to last month.
                                    Check the detailed statistics below.
                                </p>
                                <a href="{{ route('bookings.index') }}" class="btn btn-primary">View Bookings</a>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-3 px-0 px-md-4">
                                <img src="../assetsAdmin/img/illustrations/admin.png" height="140"
                                    alt="Dashboard illustration" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 order-1">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <i class='bx bx-credit-card me-1'></i>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                            <a class="dropdown-item" href="{{ route('bookings.index') }}">View More</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">Revenue</span>
                                <h4 class="card-title mb-2">${{ number_format($totalRevenue, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <i class='bx bx-calendar-check me-1'></i>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                            <a class="dropdown-item" href="{{ route('activities.index') }}">View More</a>
                                        </div>
                                    </div>
                                </div>
                                <span>Total Bookings</span>
                                <h4 class="card-title text-nowrap mb-1">{{ number_format($totalBookings) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Total Revenue -->
            <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-md-8">
                            <h5 class="card-header m-0 me-2 pb-3">Total Revenue</h5>
                            <div id="totalRevenueChart" class="px-2"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                            id="growthReportId" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            {{ date('Y') }}
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                                            <a class="dropdown-item" href="javascript:void(0);">{{ date('Y') - 1 }}</a>
                                            <a class="dropdown-item" href="javascript:void(0);">{{ date('Y') - 2 }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="growthChart"></div>
                            <div class="text-center fw-semibold pt-3 mb-2">{{ number_format($revenuePercentChange, 1) }}%
                                Growth</div>

                            <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                                <div class="d-flex">
                                    <div class="me-2">
                                        <span class="badge bg-label-primary p-2"><i
                                                class="bx bx-dollar text-primary"></i></span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small>{{ date('Y') }}</small>
                                        <h6 class="mb-0">${{ number_format($monthlyRevenue, 2) }}</h6>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="me-2">
                                        <span class="badge bg-label-info p-2"><i class="bx bx-wallet text-info"></i></span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small>{{ date('Y') - 1 }}</small>
                                        <h6 class="mb-0">${{ number_format($previousMonthRevenue, 2) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Total Revenue -->
            <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                <div class="row">
                    <div class="col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <i class="bx bx-user me-1"></i>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                            <a class="dropdown-item" href="{{ route('users.index') }}">View More</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="d-block mb-1">Users</span>
                                <h4 class="card-title text-nowrap mb-2">{{ number_format($totalUsers) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <i class='bx bx-restaurant me-1'></i>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                            <a class="dropdown-item" href="{{ route('restaurants.index') }}">View
                                                More</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">Restaurants</span>
                                <h4 class="card-title mb-2">{{ number_format($totalRestaurants) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                                    <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                        <div class="card-title">
                                            <h5 class="text-nowrap mb-2">Activities Report</h5>
                                            <span class="badge bg-label-warning rounded-pill">Year
                                                {{ date('Y') }}</span>
                                        </div>
                                        <div class="mt-sm-auto">
                                            <h4 class="mb-0">{{ number_format($totalActivities) }}</h4>
                                        </div>
                                    </div>
                                    <div id="profileReportChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Order Statistics -->
            <div class="col-md-6 col-lg-8 col-xl-8 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Order Statistics</h5>
                            <small class="text-muted">Total {{ number_format($totalBookings) }} bookings</small>
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="orederStatistics" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                                <a class="dropdown-item" href="{{ route('bookings.index') }}">View All</a>
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Category Types Section -->
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">By Category Types</h6>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <h2 class="mb-2">{{ number_format($ordersByCategory->sum('count')) }}</h2>
                                        <span>Category Bookings</span>
                                    </div>
                                    <div id="orderStatisticsChart"></div>
                                </div>
                                <ul class="p-0 m-0">
                                    @foreach ($ordersByCategory as $category)
                                        <li class="d-flex mb-3 pb-1">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <span class="avatar-initial rounded bg-label-{{ $category['color'] }}"><i
                                                        class="bx {{ $category['icon'] }}"></i></span>
                                            </div>
                                            <div
                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <h6 class="mb-0">{{ $category['name'] }}</h6>
                                                    <small class="text-muted">Category Type</small>
                                                </div>
                                                <div class="user-progress">
                                                    <small
                                                        class="fw-semibold">{{ number_format($category['count']) }}</small>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Events Section -->
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">By Events</h6>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        <h2 class="mb-2">{{ number_format($eventsByType->sum('count')) }}</h2>
                                        <span>Event Bookings</span>
                                    </div>
                                    <div id="eventStatisticsChart"></div>
                                </div>
                                <ul class="p-0 m-0">
                                    @foreach ($eventsByType as $event)
                                        <li class="d-flex mb-3 pb-1">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <span class="avatar-initial rounded bg-label-{{ $event['color'] }}"><i
                                                        class="bx {{ $event['icon'] }}"></i></span>
                                            </div>
                                            <div
                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <h6 class="mb-0">{{ $event['name'] }}</h6>
                                                    <small class="text-muted">Event Type</small>
                                                </div>
                                                <div class="user-progress">
                                                    <small
                                                        class="fw-semibold">{{ number_format($event['count']) }}</small>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Order Statistics -->

            <!-- Expense Overview -->
            <div class="col-md-6 col-lg-4 order-1 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <ul class="nav nav-pills" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-tabs-line-card-income"
                                    aria-controls="navs-tabs-line-card-income" aria-selected="true">
                                    Income
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab">Expenses</button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab">Profit</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body px-0">
                        <div class="tab-content p-0">
                            <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                                <div class="d-flex p-4 pt-3">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <img src="../assetsAdmin/img/icons/unicons/wallet.png" alt="User" />
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Monthly Revenue</small>
                                        <div class="d-flex align-items-center">
                                            <h6 class="mb-0 me-1">${{ number_format($monthlyRevenue, 2) }}</h6>
                                            <small class="text-success fw-semibold">
                                                <i class="bx bx-chevron-up"></i>
                                                {{ number_format($revenuePercentChange, 1) }}%
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div id="incomeChart"></div>
                                <div class="d-flex justify-content-center pt-4 gap-2">
                                    <div class="flex-shrink-0">
                                        <div id="expensesOfWeek"></div>
                                    </div>
                                    <div>
                                        <p class="mb-n1 mt-1">Expenses This Week</p>
                                        <small class="text-muted">Based on bookings and activities</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Expense Overview -->

            <!-- Transactions -->
            <div class="col-md-6 col-lg-4 order-2 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Recent Transactions</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                                <a class="dropdown-item" href="{{ route('bookings.index') }}">View All</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="p-0 m-0">
                            @foreach ($recentTransactions as $transaction)
                                <li class="d-flex mb-4 pb-1">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <img src="../assetsAdmin/img/icons/unicons/{{ $transaction->payment_status == 'paid' ? 'cc-success.png' : ($transaction->payment_status == 'refunded' ? 'cc-warning.png' : 'wallet.png') }}"
                                            alt="User" class="rounded" />
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <small
                                                class="text-muted d-block mb-1">{{ ucfirst($transaction->payment_status) }}</small>
                                            <h6 class="mb-0">{{ $transaction->user->first_name }}
                                                {{ $transaction->user->last_name }}</h6>
                                        </div>
                                        <div class="user-progress d-flex align-items-center gap-1">
                                            <h6 class="mb-0">${{ number_format($transaction->total_price, 2) }}</h6>
                                            <span class="text-muted">USD</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Transactions -->
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart Data
            const revenueData = @json($monthlyRevenueData);
            const revenueMonths = revenueData.map(item => item.month);
            const revenueValues = revenueData.map(item => item.revenue);

            // Category Types Chart Data
            const orderData = @json($ordersByCategory);
            const orderCount = orderData.map(item => item.count);
            const orderNames = orderData.map(item => item.name);

            // Events Chart Data
            const eventData = @json($eventsByType);
            const eventCount = eventData.map(item => item.count);
            const eventNames = eventData.map(item => item.name);

            // Weekly Expenses Data
            const weeklyData = @json($weeklyExpensesData);
            const weekDays = weeklyData.map(item => item.date);
            const weekValues = weeklyData.map(item => item.total);

            // Initialize Category Types Chart
            function reinitializeOrderChart() {
                const orderStatElement = document.getElementById('orderStatisticsChart');

                // First, find and destroy any existing ApexCharts instance
                if (window.ApexCharts) {
                    const allCharts = window.ApexCharts.getChartByID('custom-order-stats');
                    if (allCharts) {
                        allCharts.destroy();
                    }
                }

                // Remove any existing SVG elements
                const existingSvg = orderStatElement.querySelector('svg');
                if (existingSvg) {
                    existingSvg.remove();
                }

                // Clear the element completely
                orderStatElement.innerHTML = '';

                // Create our new chart with actual data but original theme colors
                return new ApexCharts(
                    orderStatElement, {
                        chart: {
                            id: 'custom-order-stats',
                            height: 165,
                            width: 130,
                            type: 'donut'
                        },
                        labels: orderNames,
                        series: orderCount,
                        // Use the original theme colors
                        colors: [
                            config.colors.primary,
                            config.colors.secondary,
                            config.colors.info,
                            config.colors.success,
                            config.colors.warning,
                            config.colors.danger
                        ],
                        stroke: {
                            width: 5,
                            colors: [config.colors.white]
                        },
                        dataLabels: {
                            enabled: false,
                            formatter: function(val) {
                                return parseInt(val) + '%';
                            }
                        },
                        legend: {
                            show: false
                        },
                        grid: {
                            padding: {
                                top: 0,
                                bottom: 0,
                                right: 15
                            }
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '75%',
                                    labels: {
                                        show: true,
                                        value: {
                                            fontSize: '1.5rem',
                                            fontFamily: 'Public Sans',
                                            color: config.colors.headingColor,
                                            offsetY: -15,
                                            formatter: function(val) {
                                                return parseInt(val);
                                            }
                                        },
                                        name: {
                                            offsetY: 20,
                                            fontFamily: 'Public Sans',
                                            show: false
                                        },
                                        total: {
                                            show: true,
                                            fontSize: '0.8125rem',
                                            color: config.colors.axisColor,
                                            label: 'Total',
                                            formatter: function(w) {
                                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                ).render();
            }

            // Initialize Events Chart
            function initializeEventChart() {
                const eventStatElement = document.getElementById('eventStatisticsChart');

                // First, check if there's an existing chart
                if (window.ApexCharts) {
                    const allCharts = window.ApexCharts.getChartByID('custom-event-stats');
                    if (allCharts) {
                        allCharts.destroy();
                    }
                }

                // Remove any existing SVG elements
                const existingSvg = eventStatElement.querySelector('svg');
                if (existingSvg) {
                    existingSvg.remove();
                }

                // Clear the element completely
                eventStatElement.innerHTML = '';

                // Create new chart for events
                return new ApexCharts(
                    eventStatElement, {
                        chart: {
                            id: 'custom-event-stats',
                            height: 165,
                            width: 130,
                            type: 'donut'
                        },
                        labels: eventNames,
                        series: eventCount,
                        // Use different colors for events
                        colors: [
                            config.colors.warning,
                            config.colors.info,
                            config.colors.success,
                            config.colors.danger,
                            config.colors.primary,
                            config.colors.secondary
                        ],
                        stroke: {
                            width: 5,
                            colors: [config.colors.white]
                        },
                        dataLabels: {
                            enabled: false,
                            formatter: function(val) {
                                return parseInt(val) + '%';
                            }
                        },
                        legend: {
                            show: false
                        },
                        grid: {
                            padding: {
                                top: 0,
                                bottom: 0,
                                right: 15
                            }
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '75%',
                                    labels: {
                                        show: true,
                                        value: {
                                            fontSize: '1.5rem',
                                            fontFamily: 'Public Sans',
                                            color: config.colors.headingColor,
                                            offsetY: -15,
                                            formatter: function(val) {
                                                return parseInt(val);
                                            }
                                        },
                                        name: {
                                            offsetY: 20,
                                            fontFamily: 'Public Sans',
                                            show: false
                                        },
                                        total: {
                                            show: true,
                                            fontSize: '0.8125rem',
                                            color: config.colors.axisColor,
                                            label: 'Total',
                                            formatter: function(w) {
                                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                ).render();
            }

            // Initialize both charts with a slight delay
            setTimeout(() => {
                reinitializeOrderChart();
                initializeEventChart();
            }, 200);

            // Also try to run them immediately
            reinitializeOrderChart();
            initializeEventChart();

            // Initialize other charts if needed...
        });
    </script>
@endsection
