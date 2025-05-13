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
    <!-- Barcode scanner library -->
    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


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
            transition: all 0.3s ease;
            z-index: 1040;
        }

        .sidebar.collapsed {
            width: 70px;
            padding: 20px 0;
        }

        .sidebar.collapsed .sidebar-title {
            display: none;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
        }

        .sidebar.collapsed .nav-link {
            text-align: center;
            padding: 10px;
        }

        .sidebar.collapsed .nav-link.dropdown-toggle::after {
            display: none;
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

        .nav-link.dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.255em;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
            float: right;
            margin-top: 8px;
        }

        #inventorySubmenu .nav-link {
            padding-left: 15px;
            font-size: 0.9rem;
        }

        #inventorySubmenu .nav-link i {
            font-size: 1rem;
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
            transition: left 0.3s ease;
        }

        .top-bar.collapsed {
            left: 70px;
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
            display: none;
            /* Remove the default dropdown arrow */
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
            width: calc(100% - 250px);
            transition: all 0.3s ease;
        }

        .main-content.collapsed {
            margin-left: 70px;
            width: calc(100% - 70px);
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

        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
                /* Keep full width on mobile */
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar.collapsed.show {
                width: 250px;
                /* Ensure sidebar is fully visible when shown on mobile */
            }

            .top-bar {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
    @stack('styles')
    @livewireStyles
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
                    <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-bar-chart-line"></i> <span>Overview</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-people"></i> <span>HR Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="#inventorySubmenu" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="inventorySubmenu">
                        <i class="bi bi-box-seam"></i> <span>Inventory</span>
                    </a>
                    <div class="collapse" id="inventorySubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link py-2" href="{{ route('admin.watches') }}">
                                    <i class="bi bi-watch"></i> <span>Watch Details</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2" href="{{ route('admin.watch-color') }}">
                                    <i class="bi bi-palette-fill"></i> <span>Watch Color</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2" href="{{ route('admin.watch-brand') }}">
                                    <i class="bi bi-tag-fill"></i> <span>Watch Brand</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2" href="{{ route('admin.watch-category') }}">
                                    <i class="bi bi-collection"></i> <span>Watch Category</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2" href="{{ route('admin.watch-dial-color') }}">
                                    <i class="bi bi-circle-half"></i> <span>Watch Dial Color</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2" href="{{ route('admin.watch-glass-type') }}">
                                    <i class="bi bi-diamond"></i> <span>Watch Glass Type</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2" href="{{ route('admin.watch-strap-color') }}">
                                    <i class="bi bi-paint-bucket"></i> <span>Watch Strap Color</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2" href="{{ route('admin.watch-strap-material') }}">
                                    <i class="bi bi-link"></i> <span>Watch Strap Material</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2" href="{{ route('admin.watch-types') }}">
                                    <i class="bi bi-grid-3x3-gap"></i> <span>Watch Type</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2" href="{{ route('admin.made-by-list') }}">
                                    <i class="bi bi-flag"></i> <span>Watch Made By</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2" href="{{ route('admin.supplier-list') }}">
                                    <i class="bi bi-truck"></i> <span>Watch Suppliers</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.billing-page') }}">
                        <i class="bi bi-cash"></i> <span>Billing</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Top Navigation Bar -->
        <nav class="top-bar">
            <!-- Add toggle button at the start of the navbar -->
            <button id="sidebarToggler" class="btn btn-sm btn-light me-auto d-flex align-items-center">
                <i class="bi bi-list fs-5"></i>
            </button>

            <div class="dropdown">
                <div class="admin-info dropdown-toggle" id="adminDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <div class="admin-avatar">A</div>
                    <div class="admin-name">Admin</div>
                </div>

                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
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
                    <li>
                        <hr class="dropdown-divider">
                    </li>
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
        <!-- Main Content -->
        <main class="main-content">
            {{ $slot }}
        </main>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 from CDN (only need this one line) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include jQuery (required by Bootstrap 4 modal) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- In your main layout file -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


    @livewireScripts

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab Switching Functionality
            const tabs = document.querySelectorAll('.content-tab');
            if (tabs.length > 0) {
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
            }

            // Chart Initialization - ONLY if element exists
            const salesChartEl = document.getElementById('salesChart');
            if (salesChartEl) {
                const ctx = salesChartEl.getContext('2d');
                new Chart(ctx, {
                    // Your existing chart configuration
                    type: 'line',
                    data: {
                        // ...existing chart data
                    },
                    options: {
                        // ...existing chart options
                    }
                });
            }

            // Inventory submenu behavior
            const inventoryToggle = document.querySelector('.nav-link.dropdown-toggle');
            const inventorySubmenu = document.querySelector('#inventorySubmenu');
            const submenuLinks = document.querySelectorAll('#inventorySubmenu .nav-link');
            const mainNavLinks = document.querySelectorAll(
                '.sidebar > .nav > .nav-item > .nav-link:not(.dropdown-toggle)');

            // Fixed function to check if any submenu items are active
            function isAnySubmenuItemActive() {
                const currentPath = window.location.pathname;
                let active = false;

                submenuLinks.forEach(link => {
                    const href = link.getAttribute('href');
                    if (href && href !== '#') {
                        // Extract just the path portion for comparison
                        const hrefPath = href.replace(/^(https?:\/\/[^\/]+)/, '').split('?')[0];
                        const linkIsActive = currentPath === hrefPath ||
                            currentPath.endsWith(hrefPath) ||
                            currentPath.includes(hrefPath);

                        if (linkIsActive) {
                            link.classList.add('active');
                            active = true;
                        }
                    }
                });

                return active;
            }

            // Initialize the submenu state on page load
            function initializeSubmenuState() {
                // If any submenu item is active based on the current URL
                if (isAnySubmenuItemActive()) {
                    // Remove active class from all main navigation links
                    mainNavLinks.forEach(link => {
                        link.classList.remove('active');
                    });

                    // Make sure the inventory toggle is active
                    inventoryToggle.classList.add('active');
                    inventoryToggle.setAttribute('aria-expanded', 'true');

                    // Make sure the submenu is expanded
                    inventorySubmenu.classList.add('show');
                }
            }

            // Handle inventory toggle clicks
            inventoryToggle.addEventListener('click', function(e) {
                e.preventDefault();
                if (inventorySubmenu.classList.contains('show')) {
                    inventorySubmenu.classList.remove('show');
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    inventorySubmenu.classList.add('show');
                    this.setAttribute('aria-expanded', 'true');
                }
            });

            // Initialize on page load
            initializeSubmenuState();

            // Add sidebar toggle functionality
            const sidebarToggler = document.getElementById('sidebarToggler');
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');

            if (sidebarToggler && sidebar) {
                sidebarToggler.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });

                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(event) {
                    const isMobile = window.innerWidth < 768;
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnToggler = sidebarToggler.contains(event.target);

                    if (isMobile && sidebar.classList.contains('show') && !isClickInsideSidebar && !
                        isClickOnToggler) {
                        sidebar.classList.remove('show');
                    }
                });

                // Handle window resize
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 768) {
                        sidebar.classList.remove('show');
                    }
                });
            }

            // Enhanced sidebar toggle functionality
            const topBar = document.querySelector('.top-bar');

            // Check if sidebar state is saved in localStorage
            const sidebarCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';

            // Initialize sidebar state
            function initializeSidebar() {
                if (sidebarCollapsed) {
                    sidebar.classList.add('collapsed');
                    topBar.classList.add('collapsed');
                    mainContent.classList.add('collapsed');
                }

                // On mobile, always start with sidebar hidden
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('show');
                    topBar.classList.remove('collapsed');
                    mainContent.classList.remove('collapsed');
                }
            }

            // Toggle sidebar function
            function toggleSidebar() {
                if (window.innerWidth < 768) {
                    // Mobile behavior
                    sidebar.classList.toggle('show');
                } else {
                    // Desktop behavior
                    sidebar.classList.toggle('collapsed');
                    topBar.classList.toggle('collapsed');
                    mainContent.classList.toggle('collapsed');

                    // Save state to localStorage
                    localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
                }
            }

            // Initialize
            if (sidebarToggler && sidebar) {
                initializeSidebar();

                // Toggle button click handler
                sidebarToggler.addEventListener('click', function(event) {
                    event.stopPropagation();
                    toggleSidebar();
                });

                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(event) {
                    if (window.innerWidth < 768 &&
                        sidebar.classList.contains('show') &&
                        !sidebar.contains(event.target) &&
                        !sidebarToggler.contains(event.target)) {
                        sidebar.classList.remove('show');
                    }
                });

                // Handle window resize
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 768) {
                        sidebar.classList.remove('show');

                        // Restore collapsed state on desktop
                        if (sidebarCollapsed) {
                            sidebar.classList.add('collapsed');
                            topBar.classList.add('collapsed');
                            mainContent.classList.add('collapsed');
                        }
                    } else {
                        // On mobile, remove collapsed styles
                        topBar.classList.remove('collapsed');
                        mainContent.classList.remove('collapsed');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
