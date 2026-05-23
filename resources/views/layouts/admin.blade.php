<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Siliwangi Rental')</title>
    
    <!-- External Assets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons & Leaflet -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ===================== CSS VARIABLES ===================== */
        :root {
            --sidebar-bg: #0F172A !important;
            --sidebar-hover: rgba(255, 255, 255, 0.04) !important;
            --sidebar-active: rgba(212, 175, 55, 0.1) !important;
            --sidebar-active-border: #D4AF37 !important;
            --sidebar-text: #94A3B8 !important;
            --sidebar-text-active: #F8FAFC !important;
            
            --topbar-bg: rgba(255, 255, 255, 0.8);
            --topbar-border: rgba(229, 231, 235, 0.6);
            
            --bg-color: #F8FAFC;
            --card-bg: #FFFFFF;
            --card-border: rgba(229, 231, 235, 0.8);
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            --card-shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            
            --text-main: #1E293B;
            --text-muted: #64748B;
            
            --primary: #0F172A;
            --secondary: #D4AF37;
            --secondary-dark: #B4941F;
            --secondary-glow: rgba(212, 175, 55, 0.3);
            
            --success: #10B981;
            --success-bg: rgba(16, 185, 129, 0.1);
            --success-text: #059669;
            
            --warning: #F59E0B;
            --warning-bg: rgba(245, 158, 11, 0.1);
            --warning-text: #D97706;
            
            --danger: #EF4444;
            --danger-bg: rgba(239, 68, 68, 0.1) !important;
            --danger-text: #EF4444 !important;
            
            --info: #3B82F6;
            --info-bg: rgba(59, 130, 246, 0.1);
            --info-text: #2563EB;
            
            --glass: rgba(255, 255, 255, 0.03) !important;
            --glass-border: rgba(255, 255, 255, 0.05) !important;
            
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 96px;
            --topbar-height: 72px;
            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-theme="dark"] {
            --sidebar-bg: #020617;
            --sidebar-hover: rgba(255, 255, 255, 0.03);
            --sidebar-active: rgba(212, 175, 55, 0.08);
            
            --topbar-bg: rgba(15, 23, 42, 0.8);
            --topbar-border: rgba(51, 65, 85, 0.4);
            
            --bg-color: #020617;
            --card-bg: #0F172A;
            --card-border: rgba(30, 41, 59, 0.6);
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
            
            --text-main: #F1F5F9;
            --text-muted: #94A3B8;
        }

        /* ===================== RESET & BASE ===================== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
            font-size: 14px;
            line-height: 1.5;
            transition: background-color var(--transition);
            -webkit-font-smoothing: antialiased;
        }

        /* Card & Premium UI */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }
        
        .card:hover {
            box-shadow: var(--card-shadow-hover);
            border-color: var(--secondary-glow);
        }

        /* Mesh Gradients Background */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: 
                radial-gradient(circle at 0% 0%, rgba(212, 175, 55, 0.03) 0%, transparent 25%),
                radial-gradient(circle at 100% 100%, rgba(59, 130, 246, 0.03) 0%, transparent 25%);
            z-index: -1;
            pointer-events: none;
        }

        /* Animations */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-up { animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        /* ===================== SIDEBAR ===================== */
        .sidebar {
            width: var(--sidebar-width) !important;
            background: #0F172A !important;
            color: #94A3B8 !important;
            position: fixed !important;
            top: 0 !important; left: 0 !important; bottom: 0 !important;
            z-index: 9999 !important;
            transition: width var(--transition);
            display: flex !important;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 24px;
            margin-bottom: 12px;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            overflow: hidden;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 20px;
            flex-shrink: 0;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
        }

        .sidebar-logo:hover .logo-icon {
            transform: scale(1.05) rotate(-5deg);
        }

        .logo-text {
            color: #FFFFFF;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: -0.5px;
            white-space: nowrap;
            transition: opacity 0.2s;
        }

        .sidebar.collapsed .logo-text {
            opacity: 0;
            pointer-events: none;
        }

        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            padding: 0 16px 24px;
        }

        .sidebar-menu::-webkit-scrollbar { width: 4px; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        .menu-label {
            padding: 24px 12px 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(148, 163, 184, 0.4);
            white-space: nowrap;
        }

        .sidebar.collapsed .menu-label {
            text-align: center;
            font-size: 0;
            padding: 24px 0 12px;
        }
        .sidebar.collapsed .menu-label::after {
            content: '•••';
            font-size: 14px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 14px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 4px;
            position: relative;
        }

        .menu-link i {
            width: 20px;
            text-align: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .menu-link .text {
            font-weight: 500;
            white-space: nowrap;
            transition: opacity 0.2s;
        }

        .sidebar.collapsed .menu-link .text {
            opacity: 0;
            position: absolute;
            left: 100%;
        }

        .menu-link:hover {
            background: var(--sidebar-hover);
            color: #FFFFFF;
            transform: translateX(4px);
        }

        .menu-item.active .menu-link {
            background: var(--sidebar-active);
            color: var(--secondary);
            box-shadow: inset 0 0 0 1px rgba(212, 175, 55, 0.2);
        }

        .menu-item.active .menu-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            bottom: 20%;
            width: 3px;
            background: var(--secondary);
            border-radius: 0 4px 4px 0;
            box-shadow: 0 0 10px var(--secondary-glow);
        }

        /* Submenu */
        .submenu {
            padding-left: 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .menu-item.open .submenu {
            max-height: 400px;
            margin-bottom: 12px;
        }

        .submenu-link {
            display: block;
            padding: 10px 16px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 13px;
            border-radius: 8px;
            transition: all 0.2s;
            position: relative;
        }

        .submenu-link:hover, .submenu-link.active {
            color: var(--secondary);
            background: rgba(212, 175, 55, 0.05);
        }

        .sidebar.collapsed .submenu {
            display: none;
        }

        /* ===================== TOPBAR ===================== */
        .topbar {
            height: var(--topbar-height);
            background: var(--topbar-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--topbar-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 40;
            transition: all var(--transition);
        }

        .toggle-sidebar {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            color: var(--text-muted);
            background: transparent;
            transition: all 0.2s;
        }

        .toggle-sidebar:hover {
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-main);
            transform: scale(1.05);
        }

        .search-container {
            position: relative;
            width: 320px;
        }

        .search-container i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
        }

        .search-container input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            background: rgba(0, 0, 0, 0.03);
            border: 1px solid transparent;
            border-radius: 14px;
            color: var(--text-main);
            font-size: 13.5px;
            transition: all 0.2s;
            outline: none;
        }

        [data-theme="dark"] .search-container input {
            background: rgba(255, 255, 255, 0.05);
        }

        .search-container input:focus {
            background: var(--card-bg);
            border-color: var(--secondary);
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .action-btn {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: var(--text-muted);
            position: relative;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .action-btn:hover {
            background: var(--card-bg);
            color: var(--secondary);
            border-color: var(--card-border);
            box-shadow: var(--card-shadow);
            transform: translateY(-2px);
        }

        .btn-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 8px;
            height: 8px;
            background: var(--danger);
            border-radius: 50%;
            border: 2px solid var(--card-bg);
        }

        .user-dropdown-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 6px 6px 16px;
            border-radius: 14px;
            background: rgba(0, 0, 0, 0.02);
            border: 1px solid transparent;
            transition: all 0.2s;
        }

        [data-theme="dark"] .user-dropdown-btn {
            background: rgba(255, 255, 255, 0.03);
        }

        .user-dropdown-btn:hover {
            background: var(--card-bg);
            border-color: var(--card-border);
            box-shadow: var(--card-shadow);
        }

        .avatar-img {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* ===================== MAIN CONTENT ===================== */
        .main-content {
            margin-left: 280px !important;
            width: calc(100% - 280px) !important;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all var(--transition);
            background: #F8FAFC !important;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        .content-body {
            padding: 32px;
            flex: 1;
        }

        /* ===================== DROPDOWNS ===================== */
        .dropdown-card {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            width: 280px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 100;
            overflow: hidden;
        }

        .dropdown.show .dropdown-card {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* Responsive Mobile */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
            .search-container {
                display: none;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <div class="admin-layout">
        
        <!-- SIDEBAR OVERLAY FOR MOBILE -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ url('/admin') }}" class="sidebar-logo">
                    <div class="logo-icon"><i class="fas fa-car-side"></i></div>
                    <span class="logo-text">Siliwangi Admin</span>
                </a>
            </div>

            <div class="sidebar-menu">
                <div class="menu-label">Analytics Hub</div>
                
                <div class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="menu-link">
                        <i class="fas fa-chart-line"></i>
                        <span class="text">Operational Summary</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="#" class="menu-link opacity-50" title="Business Intelligence Hub - Coming Soon">
                        <i class="fas fa-brain"></i>
                        <span class="text">Growth Intelligence</span>
                    </a>
                </div>

                <div class="menu-label">Operational Management</div>
                
                <div class="menu-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.bookings.index') }}" class="menu-link">
                        <i class="fas fa-calendar-check"></i>
                        <span class="text">Bookings & Schedule</span>
                    </a>
                </div>

                <div class="menu-item {{ request()->routeIs('admin.maintenances.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.maintenances.index') }}" class="menu-link">
                        <i class="fas fa-tools"></i>
                        <span class="text">Fleet Health & Maint.</span>
                        <span class="badge bg-danger ms-auto" style="font-size: 8px;">NEW</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="#fleetMap" class="menu-link">
                        <i class="fas fa-satellite-dish"></i>
                        <span class="text">Live GPS Tracking</span>
                        <span class="badge bg-primary ms-auto" style="font-size: 8px;">LIVE</span>
                    </a>
                </div>

                <div class="menu-item {{ request()->routeIs('admin.car-inspections.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.car-inspections.index') }}" class="menu-link">
                        <i class="fas fa-clipboard-check"></i>
                        <span class="text">Vehicle Inspections</span>
                    </a>
                </div>
                
                <div class="menu-item {{ request()->routeIs('admin.finance.payments') ? 'active' : '' }}">
                    <a href="{{ route('admin.finance.payments') }}" class="menu-link">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span class="text">Financial Records</span>
                    </a>
                </div>

                <div class="menu-item">
                    <div class="menu-link" onclick="toggleSubmenu(this)" style="cursor: pointer;">
                        <i class="fas fa-university"></i>
                        <span class="text">Finance Strategy</span>
                        <span class="badge bg-warning text-dark ms-auto" style="font-size: 8px;">BETA</span>
                        <i class="fas fa-chevron-right arrow" style="margin-left: 5px; font-size: 10px;"></i>
                    </div>
                    <div class="submenu">
                        <a href="#" class="submenu-link">Payment Gateway</a>
                        <a href="#" class="submenu-link">Security Deposits</a>
                        <a href="#" class="submenu-link">P&L Analysis</a>
                    </div>
                </div>

                <div class="menu-label">Asset Directory</div>

                <div class="menu-item {{ request()->routeIs('admin.cars.*') ? 'active' : '' }}">
                    <div class="menu-link" onclick="toggleSubmenu(this)" style="cursor: pointer;">
                        <i class="fas fa-car"></i>
                        <span class="text">Fleet Management</span>
                        <i class="fas fa-chevron-right arrow" style="margin-left: auto; font-size: 10px;"></i>
                    </div>
                    <div class="submenu">
                        <a href="{{ route('admin.cars.index') }}" class="submenu-link {{ request()->routeIs('admin.cars.index') ? 'active' : '' }}">Vehicle List</a>
                        <a href="{{ route('admin.car-brands.index') }}" class="submenu-link {{ request()->routeIs('admin.car-brands.*') ? 'active' : '' }}">Brand Directory</a>
                        <a href="{{ route('admin.car-types.index') }}" class="submenu-link {{ request()->routeIs('admin.car-types.*') ? 'active' : '' }}">Category Settings</a>
                    </div>
                </div>

                <div class="menu-item {{ request()->routeIs('admin.drivers.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.drivers.index') }}" class="menu-link">
                        <i class="fas fa-id-card"></i>
                        <span class="text">Active Drivers</span>
                    </a>
                </div>

                <div class="menu-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.customers.index') }}" class="menu-link">
                        <i class="fas fa-users"></i>
                        <span class="text">Customer Database</span>
                    </a>
                </div>

                <div class="menu-label">Intelligence & Settings</div>

                <div class="menu-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.index') }}" class="menu-link">
                        <i class="fas fa-file-contract"></i>
                        <span class="text">Business Reports</span>
                    </a>
                </div>

                <div class="menu-item">
                    <div class="menu-link" onclick="toggleSubmenu(this)" style="cursor: pointer;">
                        <i class="fas fa-shield-alt"></i>
                        <span class="text">Customer Governance</span>
                        <i class="fas fa-chevron-right arrow" style="margin-left: auto; font-size: 10px;"></i>
                    </div>
                    <div class="submenu">
                        <a href="#" class="submenu-link text-danger fw-bold"><i class="fas fa-user-slash me-1"></i> Blacklist</a>
                        <a href="#" class="submenu-link">Loyalty Program</a>
                        <a href="#" class="submenu-link">WA Marketing</a>
                    </div>
                </div>

                <div class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}" class="menu-link">
                        <i class="fas fa-user-shield"></i>
                        <span class="text">System Access</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- TOPBAR -->
            <header class="topbar">
                <div class="topbar-left">
                    <button class="toggle-sidebar" onclick="toggleSidebar()" title="Toggle Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="search-container">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search records, vehicles, or clients...">
                    </div>
                </div>

                <div class="topbar-right">
                    <div class="topbar-actions">
                        <!-- Theme -->
                        <button class="action-btn" onclick="toggleTheme()" title="Toggle Visual Mode">
                            <i class="fas fa-moon" id="themeIcon"></i>
                        </button>
                        
                        <!-- Notifications -->
                        <div class="dropdown" id="notifDropdown">
                            <button class="action-btn" onclick="toggleDropdown('notifDropdown')" title="Notifications">
                                <i class="far fa-bell"></i>
                                <span class="btn-badge"></span>
                            </button>
                            <div class="dropdown-card" style="width: 320px;">
                                <div style="padding: 16px; font-weight: 700; font-size: 14px; border-bottom: 1px solid var(--card-border); background: rgba(0,0,0,0.02);">
                                    Operational Updates
                                </div>
                                <div style="max-height: 300px; overflow-y: auto;">
                                    <a href="#" class="dropdown-item" style="display: flex; gap: 12px; padding: 14px 16px; text-decoration: none; border-bottom: 1px solid var(--card-border); transition: 0.2s;">
                                        <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--success-bg); color: var(--success); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; font-size: 13px; color: var(--text-main);">New Booking BK-2605</div>
                                            <div style="font-size: 11px; color: var(--text-muted);">Just now • Fleet: Pajero Sport</div>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item" style="display: flex; gap: 12px; padding: 14px 16px; text-decoration: none; border-bottom: 1px solid var(--card-border); transition: 0.2s;">
                                        <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--info-bg); color: var(--info); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="fas fa-wallet"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; font-size: 13px; color: var(--text-main);">Payment Verified</div>
                                            <div style="font-size: 11px; color: var(--text-muted);">15 mins ago • Total: Rp 1.500.000</div>
                                        </div>
                                    </a>
                                </div>
                                <a href="#" style="display: block; padding: 12px; text-align: center; font-size: 12px; font-weight: 600; color: var(--secondary); text-decoration: none; background: rgba(212,175,55,0.03);">
                                    View Activity Log
                                </a>
                            </div>
                        </div>
                        
                        <!-- WhatsApp -->
                        <button class="action-btn" title="Live Support Channel">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                    </div>

                    <!-- Profile -->
                    <div class="dropdown" id="profileDropdown">
                        <div class="user-dropdown-btn" onclick="toggleDropdown('profileDropdown')" style="cursor: pointer;">
                            <div class="user-info" style="display: block; text-align: right;">
                                <div style="font-weight: 700; font-size: 13px; color: var(--text-main); line-height: 1;">{{ Auth::user()->name }}</div>
                                <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">{{ Auth::user()->roles->first()?->name ?? 'System Admin' }}</div>
                            </div>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=D4AF37&color=0F172A&bold=true&rounded=true" class="avatar-img" alt="User">
                        </div>
                        <div class="dropdown-card" style="width: 240px;">
                            <div style="padding: 16px; border-bottom: 1px solid var(--card-border); background: rgba(0,0,0,0.02);">
                                <div style="font-weight: 700; font-size: 14px; color: var(--text-main);">{{ Auth::user()->name }}</div>
                                <div style="font-size: 11px; color: var(--text-muted); overflow: hidden; text-overflow: ellipsis;">{{ Auth::user()->email }}</div>
                            </div>
                            <div style="padding: 8px;">
                                <a href="{{ route('profile.edit') }}" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; text-decoration: none; color: var(--text-main); font-size: 13px; transition: 0.2s;">
                                    <i class="fas fa-user-circle" style="width: 18px; color: var(--text-muted);"></i> Account Settings
                                </a>
                                <a href="#" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; text-decoration: none; color: var(--text-main); font-size: 13px; transition: 0.2s;">
                                    <i class="fas fa-shield-alt" style="width: 18px; color: var(--text-muted);"></i> Privacy Policy
                                </a>
                            </div>
                            <div style="border-top: 1px solid var(--card-border); padding: 8px;">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; border: none; background: transparent; color: var(--danger); font-size: 13px; font-weight: 600; cursor: pointer; transition: 0.2s;">
                                        <i class="fas fa-sign-out-alt" style="width: 18px;"></i> Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <main class="content-body animate-up">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth <= 1024) {
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        }

        // Dropdown Toggle
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const isShowing = dropdown.classList.contains('show');
            
            // Close all dropdowns first
            document.querySelectorAll('.dropdown').forEach(el => {
                el.classList.remove('show');
            });

            // Toggle the clicked one
            if (!isShowing) {
                dropdown.classList.add('show');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown').forEach(el => {
                    el.classList.remove('show');
                });
            }
        });

        // Submenu Toggle
        function toggleSubmenu(el) {
            const parent = el.parentElement;
            parent.classList.toggle('open');
        }

        // Theme Toggle
        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('themeIcon');
            const currentTheme = html.getAttribute('data-theme');
            
            if (currentTheme === 'dark') {
                html.setAttribute('data-theme', 'light');
                icon.className = 'fas fa-moon';
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-theme', 'dark');
                icon.className = 'fas fa-sun';
                localStorage.setItem('theme', 'dark');
            }

            // Update charts if they exist
            if (typeof updateChartsTheme === 'function') {
                updateChartsTheme(html.getAttribute('data-theme'));
            }
        }

        // Init Theme
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            const icon = document.getElementById('themeIcon');
            if (savedTheme === 'dark') {
                icon.className = 'fas fa-sun';
            }
        });
    </script>
    
    @yield('scripts')
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
