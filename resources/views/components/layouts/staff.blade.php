<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Chart.js -->

    <style>
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f5f7fa;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #ffffff;
            border-right: 1px solid #e0e0e0;
            padding: 20px 0;
            position: fixed;
        }

        .sidebar-header {
            padding: 0 20px 20px;
            margin-bottom: 15px;
        }

        .sidebar-title {
            font-weight: 700;
            font-size: 1.2rem;
            color: #212529;
        }

        .nav-item {
            margin: 5px 0;
        }

        .nav-link {
            color: #495057;
            padding: 10px 20px;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: #f0f7ff;
            color: #0d6efd;
        }

        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .top-bar {
            height: 60px;
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            padding: 0 20px;
            position: fixed;
            top: 0;
            right: 0;
            left: 250px;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        .admin-info:hover {
            background-color: #f8f9fa;
        }

        .admin-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #0d6efd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
        }

        .admin-name {
            font-weight: 500;
        }

        .dropdown-toggle {
            cursor: pointer;
        }

        .dropdown-toggle::after {
            display: none; /* Remove the default dropdown arrow */
        }

        .dropdown-menu {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 8px 0;
            margin-top: 10px;
            min-width: 200px;
        }

        .dropdown-item {
            padding: 8px 16px;
            display: flex;
            align-items: center;
        }

        .dropdown-item:hover {
            background-color: #f0f7ff;
        }

        .dropdown-item i {
            font-size: 1rem;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
            min-height: calc(100vh - 60px);
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .stat-change {
            color: #28a745;
            font-size: 13px;
        }

        .stat-change-alert {
            color: #842029;
            font-size: 13px;
        }

        .content-tabs {
            display: flex;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }

        .content-tab {
            padding: 10px 20px;
            cursor: pointer;
            font-weight: 500;
            color: #495057;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
        }

        .content-tab.active {
            color: #0d6efd;
            border-bottom-color: #0d6efd;
        }

        .content-tab:hover:not(.active) {
            color: #0d6efd;
            border-bottom-color: #dee2e6;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .chart-card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            margin-bottom: 20px;
        }

        .chart-header {
            background-color: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .chart-container {
            position: relative;
            height: 300px;
            padding: 1.5rem;
        }

        .recent-sales-card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            height: 380px;
            width: 100%;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background-color: #e9ecef;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 15px;
            color: #6c757d;
            font-size: 1rem;
            font-weight: bold;
        }

        .amount {
            font-weight: bold;
            color: #198754;
        }

        .widget-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            margin-left: -10px;

            height: 100%;
            width: 600px;
        }

        .widget-header {
            margin-bottom: 15px;
        }

        .widget-header h6 {
            font-size: 1.25rem;
            margin-bottom: 5px;
            font-weight: 500;
            color: #212529;
        }

        .widget-header p {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0;
        }

        .item-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .item-details {
            flex-grow: 1;
            margin-right: 10px;
        }

        .item-details h6 {
            font-size: 1rem;
            margin-bottom: 3px;
            color: #212529;
        }

        .item-details p {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0;
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: bold;
            white-space: nowrap;
        }

        .in-stock {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .low-stock {
            background-color: #fff3cd;
            color: #664d03;
        }

        .out-of-stock {
            background-color: #f8d7da;
            color: #842029;
        }

        .progress {
            height: 0.5rem;
            margin-top: 5px;
            background-color: #e9ecef;
            border-radius: 0.25rem;
            overflow: hidden;
        }

        .progress-bar {
            background-color: #007bff;
            /* Default progress bar color */
            height: 0.5rem;
        }

        .staff-info {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .staff-status {
            margin-right: 10px;
        }

        .staff-status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: bold;
            white-space: nowrap;
        }

        .present {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .late {
            background-color: #fff3cd;
            color: #664d03;
        }

        .absent {
            background-color: #f8d7da;
            color: #842029;
        }

        .staff-details {
            flex-grow: 1;
        }

        .staff-details h6 {
            font-size: 1rem;
            margin-bottom: 3px;
            color: #212529;
        }

        .staff-details p {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 2px;
        }

        .staff-details .bi {
            margin-right: 5px;
        }

        .attendance-icon {
            margin-left: auto;
            font-size: 1.5rem;
            color: #198754;
            /* Success green */
        }

        .late-icon {
            color: #ffc107;
            /* Warning yellow  */
        }

        .absent-icon {
            color: #dc3545;
            /* Danger red  */
        }
    </style>

</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title">WatchStore</div>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="./dashbord.html">
                        <i class="bi bi-bar-chart-line"></i> Overview
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./HR.html">
                        <i class="bi bi-people"></i> HR Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./inventory.html">
                        <i class="bi bi-box-seam"></i> Inventory
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./sales.html">
                        <i class="bi bi-cart3"></i> Sales
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./billing.html">
                        <i class="bi bi-receipt"></i> Billing
                    </a>
                </li>
            </ul>
        </div>
    

        <!-- Top Navigation Bar -->
        <nav class="top-bar">
            <div class="dropdown">
                <div class="admin-info dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="admin-avatar">S</div>
                    <div class="admin-name">Staff</div>
                </div>
                
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person me-2"></i>My Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-gear me-2"></i>Settings
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="mb-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        {{ $slot }}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Tab Switching Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.content-tab');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));

                    // Add active class to clicked tab
                    this.classList.add('active');

                    // Hide all tab contents
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.remove('active');
                    });

                    // Show the selected tab content
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });

            // Chart Initialization
            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Black Friday',
                        tension: 0.4,
                        borderWidth: 3,
                        borderColor: '#00ab55',
                        backgroundColor: 'transparent',
                        data: [20, 60, 20, 50, 90, 220, 440, 380, 500],
                        pointRadius: 0
                    }, {
                        label: 'Autumn Sale',
                        tension: 0.4,
                        borderWidth: 3,
                        borderColor: '#212b36',
                        backgroundColor: 'transparent',
                        data: [30, 90, 40, 140, 290, 290, 240, 270, 230],
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#fff',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                borderDash: [2],
                                color: '#dee2e6',
                                drawBorder: false
                            },
                            ticks: {
                                beginAtZero: true,
                                padding: 10,
                                font: {
                                    size: 11,
                                    family: 'Public Sans'
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                padding: 10,
                                font: {
                                    size: 11,
                                    family: 'Public Sans'
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
