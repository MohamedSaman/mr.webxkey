<div>
    @push('styles')
        <style>
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
                height: 100%;
                width: auto;
                margin-left: 0;
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
    @endpush

    <!-- Navigation Tabs -->
    <div class="content-tabs">
        <div class="content-tab active" data-tab="overview">Overview</div>
        <div class="content-tab" data-tab="analytics">Analytics</div>
        <div class="content-tab" data-tab="reports">Reports</div>
        <div class="content-tab" data-tab="notifications">Notifications</div>
    </div>

    <!-- Overview Content -->
    <div id="overview" class="tab-content active">
        <!-- Stats Cards Row -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value">$45,231.89</div>
                    <div class="stat-change">+20.1% from last month</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-label">Inventory Items</div>
                    <div class="stat-value">+120</div>
                    <div class="stat-change">+10 in stock</div>
                    <div class="stat-change-alert">+20 out of stock</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-label">Active Staff</div>
                    <div class="stat-value">24</div>
                    <div class="stat-label">16 currently on duty</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-label">Active Sales</div>
                    <div class="stat-value">+12.5%</div>
                    <div class="stat-change">+7% from last week</div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row">
            <div class="col-md-8">
                <div class="chart-card">
                    <div class="chart-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Sales Overview</h6>
                            <p class="text-muted mb-0 small">Compare sales performance over the last 30 days</p>
                        </div>
                        <select class="form-select form-select-sm w-auto">
                            <option selected>2023</option>
                            <option value="2022">2022</option>
                        </select>
                    </div>
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Sales Section -->
            <div class="col-md-4">
                <div class="recent-sales-card">
                    <div class="card-body">
                        <div class="p-2">
                            <h6 class="card-title ">Recent Sales</h6>
                            <p class="card-subtitle text-muted small mb-3">Latest transactions processed in the system
                            </p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center">
                                <div class="avatar">JD</div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">John Doe</h6>
                                    <p class="text-muted small mb-0">john.doe@example.com</p>
                                </div>
                                <div class="amount">+$1,999.00</div>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <div class="avatar">SD</div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Sarah Davis</h6>
                                    <p class="text-muted small mb-0">sarah.davis@example.com</p>
                                </div>
                                <div class="amount">+$39.00</div>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <div class="avatar">RK</div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Robert Kim</h6>
                                    <p class="text-muted small mb-0">robert.kim@example.com</p>
                                </div>
                                <div class="amount">+$299.00</div>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <div class="avatar">LM</div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Lisa Martinez</h6>
                                    <p class="text-muted small mb-0">lisa.martinez@example.com</p>
                                </div>
                                <div class="amount">+$99.00</div>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <div class="avatar">AT</div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Alex Thompson</h6>
                                    <p class="text-muted small mb-0">alex.thompson@example.com</p>
                                </div>
                                <div class="amount">+$2,499.00</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Inventory and staff section -->
        <div class="container-fluid mt-4 p-0">
            <div class="row">
                <div class="col-md-6">
                    <div class="widget-container">
                        <div class="widget-header">
                            <h6>Inventory Status</h6>
                            <p class="text-muted small">Current stock levels and alerts</p>
                        </div>
                        <div class="item-row">
                            <div class="item-details">
                                <h6>Premium T-Shirt</h6>
                                <p class="text-muted small">SKU: TS-001</p>
                            </div>
                            <span class="status-badge in-stock">In Stock</span>
                            <div class="ms-2 text-muted small">245/300</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: 82%;"></div>
                        </div>

                        <div class="item-row mt-3">
                            <div class="item-details">
                                <h6>Designer Jeans</h6>
                                <p class="text-muted small">SKU: DJ-002</p>
                            </div>
                            <span class="status-badge low-stock">Low Stock</span>
                            <div class="ms-2 text-muted small">12/100</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: 12%;"></div>
                        </div>

                        <div class="item-row mt-3">
                            <div class="item-details">
                                <h6>Leather Wallet</h6>
                                <p class="text-muted small">SKU: LW-003</p>
                            </div>
                            <span class="status-badge in-stock">In Stock</span>
                            <div class="ms-2 text-muted small">78/150</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: 52%;"></div>
                        </div>

                        <div class="item-row mt-3">
                            <div class="item-details">
                                <h6>Wireless Headphones</h6>
                                <p class="text-muted small">SKU: WH-004</p>
                            </div>
                            <span class="status-badge out-of-stock">Out of Stock</span>
                            <div class="ms-2 text-muted small">0/50</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-danger" style="width: 0%;"></div>
                        </div>

                        <div class="item-row mt-3">
                            <div class="item-details">
                                <h6>Smart Watch</h6>
                                <p class="text-muted small">SKU: SW-005</p>
                            </div>
                            <span class="status-badge in-stock">In Stock</span>
                            <div class="ms-2 text-muted small">34/75</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: 45.33%;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="widget-container">
                        <div class="widget-header">
                            <h6>Staff Attendance</h6>
                            <p class="text-muted small">Today's attendance and location tracking</p>
                        </div>
                        <div class="staff-info">
                            <div class="staff-status">
                                <span class="staff-status-badge present">Present</span>
                            </div>
                            <div class="staff-details">
                                <h6>Emma Wilson</h6>
                                <p class="text-muted small"><i class="bi bi-briefcase-fill"></i> Sales Manager</p>
                                <p class="text-muted small"><i class="bi bi-clock-fill"></i> 08:30 AM <i
                                        class="bi bi-geo-alt-fill"></i> Main Store</p>
                            </div>
                            <div class="attendance-icon"><i class="bi bi-check-circle-fill"></i></div>
                        </div>

                        <div class="staff-info">
                            <div class="staff-status">
                                <span class="staff-status-badge present">Present</span>
                            </div>
                            <div class="staff-details">
                                <h6>Michael Brown</h6>
                                <p class="text-muted small"><i class="bi bi-briefcase-fill"></i> Sales Representative
                                </p>
                                <p class="text-muted small"><i class="bi bi-clock-fill"></i> 08:45 AM <i
                                        class="bi bi-geo-alt-fill"></i> North Branch</p>
                            </div>
                            <div class="attendance-icon"><i class="bi bi-check-circle-fill"></i></div>
                        </div>

                        <div class="staff-info">
                            <div class="staff-status">
                                <span class="staff-status-badge late">Late</span>
                            </div>
                            <div class="staff-details">
                                <h6>Sophia Garcia</h6>
                                <p class="text-muted small"><i class="bi bi-briefcase-fill"></i> Inventory Specialist
                                </p>
                                <p class="text-muted small"><i class="bi bi-clock-fill"></i> 09:15 AM <i
                                        class="bi bi-geo-alt-fill"></i> Warehouse</p>
                            </div>
                            <div class="attendance-icon late-icon"><i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                        </div>

                        <div class="staff-info">
                            <div class="staff-status">
                                <span class="staff-status-badge absent">Absent</span>
                            </div>
                            <div class="staff-details">
                                <h6>James Johnson</h6>
                                <p class="text-muted small"><i class="bi bi-briefcase-fill"></i> Sales Representative
                                </p>
                                <p class="text-muted small"><i class="bi bi-clock-fill"></i> N/A <i
                                        class="bi bi-geo-alt-fill"></i> N/A</p>
                            </div>
                            <div class="attendance-icon absent-icon"><i class="bi bi-x-circle-fill"></i></div>
                        </div>

                        <div class="staff-info">
                            <div class="staff-status">
                                <span class="staff-status-badge present">Present</span>
                            </div>
                            <div class="staff-details">
                                <h6>Olivia Lee</h6>
                                <p class="text-muted small"><i class="bi bi-briefcase-fill"></i> Customer Service</p>
                                <p class="text-muted small"><i class="bi bi-clock-fill"></i> 08:55 AM <i
                                        class="bi bi-geo-alt-fill"></i> Main Store</p>
                            </div>
                            <div class="attendance-icon"><i class="bi bi-check-circle-fill"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Analytics Content -->
    <div id="analytics" class="tab-content">
        <div class="alert alert-info">
            Analytics content will appear here when this tab is selected.
        </div>
    </div>

    <!-- Reports Content -->
    <div id="reports" class="tab-content">
        <div class="alert alert-info">
            Reports content will appear here when this tab is selected.
        </div>
    </div>

    <!-- Notifications Content -->
    <div id="notifications" class="tab-content">
        <div class="alert alert-info">
            Notifications content will appear here when this tab is selected.
        </div>
    </div>
</div>
{{-- @push('scripts') --}}

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
{{-- @endpush --}}
