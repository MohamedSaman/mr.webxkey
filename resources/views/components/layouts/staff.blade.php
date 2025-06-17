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

    <!-- Inter font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

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
            overflow-y: auto; /* Enable vertical scrolling */
            overflow-x: hidden; /* Hide horizontal overflow */
            display: flex;
            flex-direction: column;
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
            margin: 2px 0; /* Reduced from 5px to tighten up vertical spacing */
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

        #inventorySubmenu .nav-link,
        #salesSubmenu .nav-link {
            padding-top: 6px; /* Reduced vertical padding */
            padding-bottom: 6px;
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
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            border: none;
            padding: 1.25rem;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .stat-label {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 5px;
            font-size: 0.875rem;
            font-weight: 500;
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
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .chart-header {
            background-color: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            background-color: #ffffff;
            padding: 1.25rem;
        }

        .chart-container {
            position: relative;
            height: 300px;
            padding: 1.5rem;
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
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 1.25rem;
        }

        .widget-header {
            margin-bottom: 15px;
        }

        .widget-header h6 {
            font-size: 1.25rem;
            margin-bottom: 5px;
            font-weight: 500;
            color: #212529;
            font-weight: 600;
            letter-spacing: -0.02em;
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
            font-weight: 500;
            border-radius: 6px;
            padding: 0.35rem 0.65rem;
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
                height: 100vh; /* Full height */
                top: 0;
                bottom: 0;
                box-shadow: none;
                z-index: 1050; /* Higher than topbar */
                position: fixed;
                overflow-y: auto;
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            }

            /* Ensure collapsed styles don't affect mobile visibility */
            .sidebar.collapsed.show {
                width: 250px;
            }
            
            .sidebar.collapsed .nav-link span {
                display: inline; /* Override desktop collapsed styles on mobile */
            }
            
            .sidebar.collapsed .sidebar-title {
                display: block; /* Override desktop collapsed styles on mobile */
            }
            
            .sidebar.collapsed .nav-link i {
                margin-right: 10px; /* Restore margin on mobile */
                font-size: 1.1rem; /* Restore size on mobile */
            }
            
            .sidebar.collapsed .nav-link {
                text-align: left; /* Restore text alignment on mobile */
                padding: 10px 20px; /* Restore padding on mobile */
            }

            .top-bar {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Add these styles at the end of your existing style block */
        
        /* Updated font family to include Inter as first option */
        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            letter-spacing: -0.01em;
        }
        
        /* Refine typography for better readability */
        .sidebar-title {
            font-weight: 600;
            letter-spacing: -0.02em;
        }
        
        .nav-link.active {
            background-color: #e9f0ff;
            font-weight: 500;
        }
        
        .content-tab.active {
            font-weight: 600;
        }
        
        /* Cleaner stats cards */
        .stat-card {
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            border: none;
            padding: 1.25rem;
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        
        .stat-label {
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        /* More modern chart cards */
        .chart-card {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        
        .chart-header {
            background-color: #ffffff;
            padding: 1.25rem;
        }
        
        .chart-container {
            padding: 1.5rem;
        }
        
        /* Improved widget containers */
        .widget-container {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 1.25rem;
        }
        
        .widget-header h6 {
            font-weight: 600;
            letter-spacing: -0.02em;
        }
        
        /* Better badges */
        .status-badge {
            font-weight: 500;
            border-radius: 6px;
            padding: 0.35rem 0.65rem;
        }
        
        /* Horizontal scrolling for charts */
        .chart-scroll-container {
            width: 100%;
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: #dee2e6 #f8f9fa;
        }
        
        .chart-scroll-container::-webkit-scrollbar {
            height: 6px;
        }
        
        .chart-scroll-container::-webkit-scrollbar-track {
            background: #f8f9fa;
        }
        
        .chart-scroll-container::-webkit-scrollbar-thumb {
            background-color: #dee2e6;
            border-radius: 10px;
        }
        
        /* Scrollable containers */
        .inventory-container,
        .staff-sales-container {
            scrollbar-width: thin;
            scrollbar-color: #dee2e6 #f8f9fa;
        }
        
        .inventory-container::-webkit-scrollbar,
        .staff-sales-container::-webkit-scrollbar {
            width: 6px;
        }
        
        .inventory-container::-webkit-scrollbar-track,
        .staff-sales-container::-webkit-scrollbar-track {
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .inventory-container::-webkit-scrollbar-thumb,
        .staff-sales-container::-webkit-scrollbar-thumb {
            background-color: #dee2e6;
            border-radius: 10px;
        }
        
        /* Avatar styling */
        .admin-avatar, .staff-avatar, .avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            letter-spacing: -0.03em;
        }

        /* Add padding to bottom of nav to ensure last items are visible */
        .sidebar .nav.flex-column {
            padding-bottom: 80px; /* Extra space at bottom to ensure visibility of last items */
        }

        /* Improve scrollbar styling */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f8f9fa;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: #dee2e6;
            border-radius: 6px;
        }

        /* For Firefox */
        .sidebar {
            scrollbar-width: thin;
            scrollbar-color: #dee2e6 #f8f9fa;
        }

        /* Fix for iOS momentum scrolling */
        @@supports (-webkit-overflow-scrolling: touch) {
            .sidebar {
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Add these styles for scroll indicator */
        .sidebar.scrollable::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: linear-gradient(to top, rgba(255,255,255,0.9), rgba(255,255,255,0));
            pointer-events: none;
            z-index: 2;
        }
        
        .sidebar.collapsed.scrollable::after {
            width: 70px;
        }
        
        /* Fix navigation spacing issues */
        .nav-item {
            margin: 2px 0; /* Reduced from 5px to tighten up vertical spacing */
        }
        
        #inventorySubmenu .nav-link,
        #salesSubmenu .nav-link {
            padding-top: 6px; /* Reduced vertical padding */
            padding-bottom: 6px;
        }
        
        .collapse .nav.flex-column {
            padding-bottom: 0; /* Remove extra bottom padding from nested menus */
        }
        
        .collapse .nav-item:last-child {
            margin-bottom: 3px; /* Add small space after last submenu item */
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
            <div class="sidebar-content">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('staff.dashboard') }}">
                            <i class="bi bi-bar-chart-line"></i> <span>Dashboard</span>
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
                                    <a class="nav-link py-2" href="{{ route('staff.staff-stock-overview') }}">
                                        <i class="bi bi-watch"></i> <span>Stock Details</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#salesSubmenu" data-bs-toggle="collapse"
                            role="button" aria-expanded="false" aria-controls="salesSubmenu">
                            <i class="bi bi-cart"></i> <span>Sales</span>
                        </a>
                        <div class="collapse" id="salesSubmenu">
                            <ul class="nav flex-column ms-3">
                                <li class="nav-item">
                                    <a class="nav-link py-2" href="{{ route('staff.customer-sale-management') }}">
                                        <i class="bi bi-people"></i> <span>Customer Sales</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link py-2" href="{{ route('staff.due-payments') }}">
                                        <i class="bi bi-cash-coin"></i> <span>Customer Due</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('staff.billing') }}">
                            <i class="bi bi-cash"></i> <span>Billing</span>
                        </a>
                    </li>
                </ul>
            </div>
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
                    <div class="admin-avatar">S</div>
                    <div class="admin-name">Staff</div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Define all elements once
            const sidebarToggler = document.getElementById('sidebarToggler');
            const sidebar = document.querySelector('.sidebar');
            const topBar = document.querySelector('.top-bar');
            const mainContent = document.querySelector('.main-content');

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

            // Improved menu activation logic
            function setActiveMenu() {
                const currentPath = window.location.pathname;
                let activeSubmenuFound = false;
                
                // First, check all menu links in the sidebar
                document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                    // Reset all links to inactive state first
                    link.classList.remove('active');
                    
                    // Get the link's href attribute
                    const href = link.getAttribute('href');
                    if (href && href !== '#' && !href.startsWith('#')) {
                        // Extract just the path portion of the href
                        const hrefPath = href.replace(/^(https?:\/\/[^\/]+)/, '').split('?')[0];
                        
                        // Use more precise path matching logic
                        const isActive = currentPath === hrefPath || 
                                        (currentPath.startsWith(hrefPath + '/') && hrefPath !== '/') ||
                                        (currentPath === hrefPath + '.php');
                                        
                        if (isActive) {
                            // This link is active
                            link.classList.add('active');
                            
                            // If this is a submenu link, expand and highlight the parent menu
                            const submenu = link.closest('.collapse');
                            if (submenu) {
                                activeSubmenuFound = true;
                                
                                // Add 'show' class to submenu to keep it expanded
                                submenu.classList.add('show');
                                
                                // Find and activate the parent dropdown toggle
                                const parentToggle = document.querySelector(`[data-bs-toggle="collapse"][href="#${submenu.id}"]`);
                                if (parentToggle) {
                                    parentToggle.classList.add('active');
                                    parentToggle.setAttribute('aria-expanded', 'true');
                                }
                            }
                        }
                    }
                });
                
                // If no submenu item is active, check if we need to activate a main nav item
                if (!activeSubmenuFound) {
                    // Get the route base path segments (e.g., /staff/billing → ["staff", "billing"])
                    const pathSegments = currentPath.split('/').filter(Boolean);
                    
                    // Only check main items if we have path segments
                    if (pathSegments.length > 0) {
                        document.querySelectorAll('.sidebar > .sidebar-content > .nav > .nav-item > .nav-link:not(.dropdown-toggle)').forEach(link => {
                            const href = link.getAttribute('href');
                            if (href && href !== '#') {
                                const hrefPath = href.replace(/^(https?:\/\/[^\/]+)/, '').split('?')[0];
                                const hrefSegments = hrefPath.split('/').filter(Boolean);
                                
                                // Only match exact routes or next level child routes
                                const isActive = hrefPath === currentPath || 
                                                (hrefSegments.length > 0 && 
                                                 pathSegments.length > 0 &&
                                                 hrefSegments[hrefSegments.length-1] === pathSegments[pathSegments.length-1]);
                                    
                                if (isActive) {
                                    link.classList.add('active');
                                }
                            }
                        });
                    }
                }
            }

            // Call the improved function instead of the old ones
            setActiveMenu();

            // Initialize sidebar state based on screen size
            function initializeSidebar() {
                // Existing code...
            }

            // Toggle sidebar function - unified for mobile and desktop
            function toggleSidebar(event) {
                if (event) {
                    event.stopPropagation();
                }
                
                const isMobile = window.innerWidth < 768;
                
                if (isMobile) {
                    // Mobile behavior - toggle show class
                    sidebar.classList.toggle('show');
                    
                    // Ensure no collapsed classes are present on mobile
                    sidebar.classList.remove('collapsed');
                    topBar.classList.remove('collapsed');
                    mainContent.classList.remove('collapsed');
                } else {
                    // Desktop behavior - toggle collapsed classes
                    sidebar.classList.toggle('collapsed');
                    topBar.classList.toggle('collapsed');
                    mainContent.classList.toggle('collapsed');
                    
                    // Save state to localStorage
                    localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
                }
            }

            // Adjust sidebar height
            function adjustSidebarHeight() {
                if (sidebar) {
                    // Ensure sidebar takes full viewport height
                    sidebar.style.height = `${window.innerHeight}px`;
                    
                    // Check if content is taller than viewport
                    const sidebarNav = sidebar.querySelector('.nav.flex-column');
                    if (sidebarNav) {
                        const needsScroll = sidebarNav.scrollHeight > window.innerHeight;
                        if (needsScroll) {
                            sidebar.classList.add('scrollable');
                        } else {
                            sidebar.classList.remove('scrollable');
                        }
                    }
                }
            }

            // Initialize sidebar
            if (sidebar) {
                initializeSidebar();
                
                // Attach toggle event listener (single source of truth)
                if (sidebarToggler) {
                    sidebarToggler.addEventListener('click', toggleSidebar);
                }

                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(event) {
                    const isMobile = window.innerWidth < 768;
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnToggler = sidebarToggler && sidebarToggler.contains(event.target);

                    if (isMobile && 
                        sidebar.classList.contains('show') && 
                        !isClickInsideSidebar && 
                        !isClickOnToggler) {
                        sidebar.classList.remove('show');
                    }
                });

                // Handle window resize - switch between mobile and desktop modes
                window.addEventListener('resize', function() {
                    const wasMobile = mainContent.style.marginLeft === '0px' || mainContent.style.marginLeft === '';
                    const isMobile = window.innerWidth < 768;
                    
                    // Only run when crossing the mobile/desktop threshold
                    if (wasMobile !== isMobile) {
                        initializeSidebar();
                    }
                });

                // Adjust sidebar height initially and on resize
                adjustSidebarHeight();
                window.addEventListener('resize', adjustSidebarHeight);

                // Fix submenu scroll visibility
                const dropdownToggles = document.querySelectorAll('.nav-link.dropdown-toggle');
                dropdownToggles.forEach(toggle => {
                    toggle.addEventListener('click', function(event) {
                        // Wait for submenu to fully appear
                        setTimeout(() => {
                            const submenu = this.nextElementSibling;
                            if (submenu && submenu.classList.contains('show')) {
                                // Check if submenu bottom is out of view
                                const submenuRect = submenu.getBoundingClientRect();
                                const sidebarRect = sidebar.getBoundingClientRect();
                                
                                if (submenuRect.bottom > sidebarRect.bottom) {
                                    // Scroll to make submenu visible
                                    submenu.scrollIntoView({ behavior: 'smooth', block: 'end' });
                                }
                            }
                        }, 300);
                    });
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
