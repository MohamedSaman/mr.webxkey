<div>
    @push('styles')
        <style>
            /* Base styles */
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

            .chart-scroll-container {
                overflow-x: auto;
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

            /* Stats progress bars */
            .stat-card .progress {
                height: 6px;
                margin-bottom: 5px;
            }

            .stat-card .progress-bar {
                height: 6px;
            }

            .stat-info small, .stat-change-alert small {
                font-size: 12px;
            }

            .staff-avatar {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 32px;
                height: 32px;
            }
            
            .staff-card {
                border-left: 3px solid #0d6efd;
                transition: all 0.2s ease;
            }
            
            .staff-card:hover {
                transform: translateY(-2px);
            }
            
            .progress {
                background-color: #e9ecef;
                border-radius: 0.25rem;
                overflow: hidden;
            }
            
            .btn-outline-primary, .btn-outline-secondary {
                font-size: 0.8rem;
                font-weight: 500;
                border-radius: 6px;
                padding: 0.3rem 0.7rem;
                transition: all 0.15s ease;
            }

            .btn-outline-primary:hover, .btn-outline-secondary:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            
            /* MOBILE RESPONSIVE STYLES */
            @media (max-width: 768px) {
                .stat-card {
                    padding: 12px;
                    margin-bottom: 15px;
                }
                
                .stat-value {
                    font-size: 20px !important;
                }
                
                .stat-info small, .stat-change-alert small {
                    font-size: 11px !important;
                    white-space: normal !important;
                }
                
                .content-tab {
                    padding: 8px 12px !important;
                    white-space: nowrap;
                }
                
                .chart-header {
                    padding: 0.75rem !important;
                    flex-direction: column !important;
                    align-items: flex-start !important;
                }
                
                .chart-header .btn {
                    margin-top: 0.5rem;
                    font-size: 0.8rem;
                }
                
                .chart-header h6 {
                    font-size: 1rem;
                }
                
                .chart-header p {
                    font-size: 0.75rem;
                }
                
                .widget-container {
                    padding: 15px;
                }
                
                .item-row {
                    flex-wrap: wrap;
                }
                
                .item-details {
                    width: 100%;
                    margin-bottom: 5px;
                }
                
                .avatar {
                    width: 32px;
                    height: 32px;
                    margin-right: 10px;
                }
                
                .amount {
                    font-size: 13px;
                }
                
                .staff-card .d-flex {
                    flex-wrap: wrap;
                }
                
                .staff-card .d-flex .d-flex {
                    margin-top: 5px;
                    justify-content: space-between !important;
                    width: 100%;
                }
                
                .staff-card {
                    padding: 10px !important;
                }
                
                .staff-card h6 {
                    font-size: 0.9rem;
                }
                
                .progress {
                    height: 5px !important;
                }
                
                .recent-sales-card {
                    height: auto;
                    max-height: 380px;
                }
            }
            
            @media (max-width: 576px) {
                .content-tabs {
                    margin-bottom: 15px;
                }
                
                .stat-card {
                    padding: 10px;
                }
                
                .stat-value {
                    font-size: 18px !important;
                }
                
                .status-badge {
                    padding: 0.15rem 0.35rem;
                    font-size: 0.7rem;
                }
                
                .widget-header h6 {
                    font-size: 1rem;
                }
                
                .widget-header p {
                    font-size: 0.75rem;
                }
                
                .item-row {
                    align-items: flex-start;
                }
                
                .item-details h6 {
                    font-size: 0.9rem;
                }
                
                .d-flex-mobile-column {
                    flex-direction: column !important;
                }
                
                .justify-content-mobile-between {
                    justify-content: space-between !important;
                }
                
                .mb-mobile-2 {
                    margin-bottom: 0.5rem !important;
                }
                
                .w-mobile-100 {
                    width: 100% !important;
                }
                
                .text-truncate-mobile {
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    max-width: 100%;
                }
            }
            
            /* Fix for horizontal scrolling on mobile */
            .container-fluid {
                width: 100%;
                padding-right: var(--bs-gutter-x, 0.75rem);
                padding-left: var(--bs-gutter-x, 0.75rem);
                margin-right: auto;
                margin-left: auto;
                overflow-x: hidden;
            }
        </style>
    @endpush

    <!-- Navigation Tabs -->
    <div class="content-tabs overflow-auto">
        <div class="d-flex">
            <div class="content-tab active" data-tab="overview">Overview</div>
            <div class="content-tab" data-tab="analytics">Analytics</div>
            <div class="content-tab" data-tab="reports">Reports</div>
            <div class="content-tab" data-tab="notifications">Notifications</div>
        </div>
    </div>

    <!-- Overview Content -->
    <div id="overview" class="tab-content active">
        <!-- Stats Cards Row -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="stat-label">Total Revenue</div>
                        {{-- <a href="" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-right"></i>
                        </a> --}}
                    </div>
                    <div class="stat-value">Rs.{{ number_format($totalRevenue, 2) }}</div>
                    <div class="stat-info mt-1">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Revenue</small>
                            <small>{{ $revenuePercentage }}% of total sales</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $revenuePercentage }}%;" 
                                 aria-valuenow="{{ $revenuePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted text-truncate-mobile">Rs.{{ number_format($totalRevenue) }} of Rs.{{ number_format($totalRevenue+$totalDueAmount) }}</small>
                        </div>
                    </div>
                    
                    <!-- Added Fully Paid Invoices Information -->
                    <div class="stat-info mt-3 pt-2 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="bi bi-check-circle-fill text-success me-1"></i> Fully Paid</small>
                            <span class="badge bg-success">{{ $fullPaidCount }}</span>
                        </div>
                        <small class="d-block text-end text-success">Rs.{{ number_format($fullPaidAmount, 2) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="stat-label">Total Due Amount</div>
                    </div>
                    <div class="stat-value">Rs.{{ number_format($totalDueAmount, 2) }}</div>
                    <div class="stat-change-alert">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Due Amount</small>
                            <small>{{ $duePercentage }}% of total sales</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $duePercentage }}%;" 
                                 aria-valuenow="{{ $duePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted text-truncate-mobile">Rs.{{ number_format($totalDueAmount) }} due of {{ number_format($totalDueAmount+$totalRevenue) }}</small>
                        </div>
                    </div>
                    
                    <!-- Partial Payment Info -->
                    <div class="stat-info mt-3 pt-2 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="bi bi-clock-fill text-danger me-1"></i> Partially Paid</small>
                            <span class="badge bg-danger">{{ $partialPaidCount }}</span>
                        </div>
                        <small class="d-block text-end text-danger">Rs.{{ number_format($totalDueAmount, 2) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="stat-label">Inventory Status</div>
                    </div>
                    <div class="stat-value">{{ number_format($totalStock) }} <span class="fs-6 text-muted">units</span></div>
                    
                    <!-- Sales Progress -->
                    <div class="stat-info">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Sold Stock</small>
                            <small>{{ $soldPercentage }}% of assigned</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $soldPercentage }}%;" 
                                 aria-valuenow="{{ $soldPercentage }}" 
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted text-truncate-mobile">{{ number_format($soldStock) }} sold of {{ number_format($assignedStock) }}</small>
                        </div>
                    </div>
                    
                    <!-- Damaged Stock Info -->
                    <div class="stat-info mt-3 pt-2 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="bi bi-exclamation-triangle-fill text-primary me-1"></i>Available Stock</small>
                            <span class="badge bg-primary">{{ number_format($availableStock) }}</span>
                        </div>
                        <small class="d-block text-end text-primary">Rs.{{ number_format($totalAvailableInventory, 2) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="stat-label">Staff Status</div>
                    </div>
                    <div class="stat-value">{{ $totalStaffCount }} <span class="fs-6 text-muted">members</span></div>
                    
                    <!-- Staff Product Assignment Progress -->
                    <div class="stat-info mt-1">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Staff with Products</small>
                            <small>{{ $staffAssignmentPercentage }}% of total</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" 
                                style="width: {{ $staffAssignmentPercentage }}%;" 
                                aria-valuenow="{{ $staffAssignmentPercentage }}" 
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted text-truncate-mobile">{{ $staffWithAssignmentsCount }} staff with assignments</small>
                        </div>
                    </div>
                    
                    <!-- Assigned Stock Info -->
                    <div class="stat-info mt-3 pt-2 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="bi bi-person-check-fill text-info me-1"></i> Assigned</small>
                            <span class="badge bg-info">{{ $assignedStock }}</span>
                        </div>
                        <small class="d-block text-end text-info">Rs.{{number_format($totalStaffSalesValue, 2)}}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row">
            <div class="col-lg-8 col-md-12 mb-4">
                <div class="chart-card">
                    <div class="chart-header d-flex justify-content-between align-items-center flex-wrap">
                        <div class="mb-mobile-2">
                            <h6 class="mb-1">Sales Overview By Brands</h6>
                            <p class="text-muted mb-0 small">Compare sales performance Base Watch Brands</p>
                        </div>
                        <a href="" class="btn btn-sm btn-outline-primary">
                            View Report <i class="bi bi-bar-chart-line"></i>
                        </a>
                    </div>
                    <!-- Add scrollable wrapper for the chart -->
                    <div class="chart-scroll-container">
                        <div class="chart-container" style="min-width: {{ count($brandSales) * 60 }}px;">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Sales Section -->
            <div class="col-lg-4 col-md-12 mb-4">
                <div class="recent-sales-card">
                    <div class="card-body">
                        <div class="p-2 d-flex justify-content-between align-items-start flex-wrap">
                            <div class="mb-2 mb-md-0">
                                <h6 class="card-title">Recent Sales</h6>
                                <p class="card-subtitle text-muted small mb-0">Latest transactions</p>
                            </div>
                            <a href="" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-list-ul"></i>
                            </a>
                        </div>
                        <ul class="list-group list-group-flush">
                            @forelse($recentSales as $sale)
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="avatar">
                                        {{ strtoupper(substr($sale->name, 0, 1)) }}{{ strtoupper(substr(strpos($sale->name, ' ') !== false ? substr($sale->name, strpos($sale->name, ' ') + 1, 1) : '', 0, 1)) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-truncate-mobile">{{ $sale->name }}</h6>
                                        <p class="text-muted small mb-0 text-truncate-mobile">{{ $sale->email }}</p>
                                    </div>
                                    <div class="amount">
                                        +Rs.{{ number_format($sale->total_amount, 2) }}
                                        @if($sale->due_amount > 0)
                                            <span class="d-block text-danger small text-end">Rs.{{ number_format($sale->due_amount, 2) }}</span>
                                        @else
                                            <span class="d-block badge bg-success mt-1 small">Paid</span>
                                        @endif
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center">
                                    <p class="text-muted mb-0">No sales recorded yet</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Inventory and staff section -->
        <div class="container-fluid mt-4 p-0">
            <div class="row">
                <div class="col-lg-5 col-md-12 mb-4">
                    <div class="widget-container">
                        <div class="widget-header d-flex justify-content-between align-items-start flex-wrap">
                            <div class="mb-2 mb-md-0">
                                <h6>Inventory Status</h6>
                                <p class="text-muted small mb-0">Current stock levels and alerts</p>
                            </div>
                            <a href="{{ route('admin.watch-stock-details') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-box-seam"></i>
                            </a>
                        </div>
                        
                        <!-- Scrollable container WITHOUT footer -->
                        <div class="inventory-container" style="max-height: 400px; overflow-y: auto;">
                            @forelse($watchInventory as $watch)
                                @php
                                    // Calculate stock percentage and status
                                    $stockPercentage = $watch->total_stock > 0 ? 
                                        round(($watch->available_stock / $watch->total_stock) * 100, 2) : 0;
                                    
                                    // Determine stock status badge
                                    if ($watch->available_stock == 0) {
                                        $statusClass = 'out-of-stock';
                                        $statusText = 'Out of Stock';
                                        $progressClass = 'bg-danger';
                                    } elseif ($stockPercentage <= 25) {
                                        $statusClass = 'low-stock';
                                        $statusText = 'Low Stock';
                                        $progressClass = 'bg-warning';
                                    } else {
                                        $statusClass = 'in-stock';
                                        $statusText = 'In Stock';
                                        $progressClass = '';
                                    }
                                @endphp

                                <div class="item-row @if(!$loop->first) mt-3 @endif">
                                    <div class="item-details">
                                        <h6 class="text-truncate-mobile">{{ $watch->name }} {{ $watch->model }}</h6>
                                        <p class="text-muted small text-truncate-mobile">SKU: {{ $watch->code }}</p>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap mt-1 mt-md-0">
                                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                                        <div class="ms-2 text-muted small">{{ $watch->available_stock }}/{{ $watch->total_stock }}</div>
                                    </div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar {{ $progressClass }}" style="width: {{ $stockPercentage }}%;"></div>
                                </div>
                            @empty
                                <div class="alert alert-info">No watch inventory data available.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <!-- Staff Sales Section -->
                <div class="col-lg-7 col-md-12 mb-4">
                    <div class="widget-container p-3">
                        <div class="widget-header mb-3 d-flex justify-content-between align-items-start flex-wrap">
                            <div class="mb-2 mb-md-0">
                                <h6 class="fw-bold">Staff Sales</h6>
                                <p class="text-muted small mb-0">Sales performance and collection status</p>
                            </div>
                            <a href="" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-people"></i>
                            </a>
                        </div>
                        
                        <!-- Scrollable container WITHOUT footer -->
                        <div class="staff-sales-container" style="max-height: 400px; overflow-y: auto;">
                            @forelse($staffSales as $staff)
                                <div class="staff-card p-3 mb-3 bg-light rounded shadow-sm">
                                    <div class="d-flex align-items-start mb-2">
                                        <div class="staff-avatar me-2">
                                            <span class="badge bg-primary bg-opacity-25 text-white fw-medium py-2 px-2">
                                                {{ strtoupper(substr($staff->name, 0, 1)) }}{{ strtoupper(substr(strpos($staff->name, ' ') !== false ? substr($staff->name, strpos($staff->name, ' ') + 1, 1) : '', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-0 text-truncate-mobile">{{ $staff->name }}</h6>
                                        </div>
                                    </div>
                                    
                                    <!-- Sales Progress Section -->
                                    <div class="sales-progress mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
                                            <small class="text-muted">Sales Progress</small>
                                            <div class="d-flex align-items-center flex-wrap">
                                                <small class="me-2 text-success fw-bold">Rs.{{ number_format($staff->sold_value, 2) }}</small>
                                                <small class="text-muted">/ Rs.{{ number_format($staff->assigned_value, 2) }}</small>
                                                <span class="badge bg-success ms-2">{{ $staff->sales_percentage }}%</span>
                                            </div>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $staff->sales_percentage }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Payment Progress Section -->
                                    <div class="payment-progress">
                                        <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
                                            <small class="text-muted">Payment Collection</small>
                                            <div class="d-flex align-items-center flex-wrap">
                                                <small class="me-2 text-success fw-bold">Rs.{{ number_format($staff->collected_amount, 2) }}</small>
                                                <small class="text-danger fw-bold">- Rs.{{ number_format($staff->total_due, 2) }} due</small>
                                                <span class="badge {{ $staff->payment_percentage >= 80 ? 'bg-success' : 'bg-danger' }} ms-2">{{ $staff->payment_percentage }}%</span>
                                            </div>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar {{ $staff->payment_percentage >= 80 ? 'bg-success' : 'bg-danger' }}" role="progressbar" style="width: {{ $staff->payment_percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info">No staff sales data available.</div>
                            @endforelse
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

<script>
    // Prepare brand sales data from PHP
    const brandLabels = @json(collect($brandSales)->pluck('brand'));
    const brandTotals = @json(collect($brandSales)->pluck('total_sales'));

    document.addEventListener('DOMContentLoaded', function() {
        // Tab Switching Functionality
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

        // Chart Initialization for Brand Sales
        const ctx = document.getElementById('salesChart').getContext('2d');
        let chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: brandLabels,
                datasets: [{
                    label: 'Sales by Brand',
                    backgroundColor: '#007bff',
                    borderColor: '#007bff',
                    borderWidth: 1,
                    data: brandTotals
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { 
                        enabled: true,
                        displayColors: false,
                        bodyFont: {
                            size: window.innerWidth < 768 ? 12 : 14
                        },
                        titleFont: {
                            size: window.innerWidth < 768 ? 12 : 14
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#dee2e6' },
                        ticks: {
                            font: {
                                size: window.innerWidth < 768 ? 10 : 12
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: {
                                size: window.innerWidth < 768 ? 10 : 12
                            }
                        }
                    }
                }
            }
        });
        
        // Handle window resize for better chart responsiveness
        window.addEventListener('resize', function() {
            if (chartInstance) {
                // Update font sizes based on screen width
                chartInstance.options.plugins.tooltip.bodyFont.size = window.innerWidth < 768 ? 12 : 14;
                chartInstance.options.plugins.tooltip.titleFont.size = window.innerWidth < 768 ? 12 : 14;
                chartInstance.options.scales.y.ticks.font.size = window.innerWidth < 768 ? 10 : 12;
                chartInstance.options.scales.x.ticks.font.size = window.innerWidth < 768 ? 10 : 12;
                chartInstance.update();
            }
        });
    });
</script>
