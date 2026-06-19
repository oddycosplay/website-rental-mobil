<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Operations Command Center - Siliwangi Rental" />
        <meta name="author" content="Siliwangi Rental" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>@yield('title', 'Operations') | Siliwangi Admin</title>
        
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&swap" rel="stylesheet">

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="{{ asset('sbadmin/css/styles.css') }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

        <style>
            :root {
                --gold: #D4AF37;
                --gold-light: #F9E29B;
                --gold-dark: #A68A2D;
                --slate-dark: #080E1A;
                --slate-main: #0F172A;
                --slate-light: #1E293B;
                --text-main: #F8FAFC;
                --text-muted: #94A3B8;
                --card-bg: rgba(30, 41, 59, 0.5);
                --card-border: rgba(255, 255, 255, 0.08);
            }

            body {
                font-family: 'Inter', sans-serif;
                background-color: var(--slate-dark);
                color: var(--text-main);
            }

            h1, h2, h3, h4, h5, h6 {
                font-family: 'Poppins', sans-serif;
                font-weight: 700;
            }

            /* Premium Overrides for SB Admin */
            .sb-nav-fixed #layoutSidenav #layoutSidenav_nav .sb-sidenav {
                background-color: var(--slate-main);
                border-right: 1px solid var(--card-border);
            }

            .sb-topnav.navbar-dark.bg-dark {
                background-color: var(--slate-main) !important;
                border-bottom: 1px solid var(--card-border);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
            }

            .sb-sidenav-dark .sb-sidenav-menu .nav-link {
                color: var(--text-muted);
                font-size: 0.85rem;
                font-weight: 600;
                padding: 0.75rem 1.25rem;
                transition: all 0.3s ease;
            }

            .sb-sidenav-dark .sb-sidenav-menu .nav-link:hover {
                color: var(--gold);
                background-color: rgba(212, 175, 55, 0.05);
            }

            .sb-sidenav-dark .sb-sidenav-menu .nav-link.active {
                color: var(--gold);
                background-color: rgba(212, 175, 55, 0.1);
            }

            .sb-sidenav-dark .sb-sidenav-menu .nav-link .sb-nav-link-icon {
                color: var(--text-muted);
                transition: color 0.3s ease;
            }

            .sb-sidenav-dark .sb-sidenav-menu .nav-link:hover .sb-nav-link-icon,
            .sb-sidenav-dark .sb-sidenav-menu .nav-link.active .sb-nav-link-icon {
                color: var(--gold);
            }

            .sb-sidenav-menu-heading {
                color: var(--gold) !important;
                font-size: 0.7rem !important;
                font-weight: 800 !important;
                letter-spacing: 0.1em;
                text-transform: uppercase;
                opacity: 0.8;
                padding: 1.5rem 1.25rem 0.5rem !important;
            }

            .card {
                background-color: var(--card-bg);
                border: 1px solid var(--card-border);
                border-radius: 1rem;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }

            .card-header {
                background-color: rgba(255, 255, 255, 0.03);
                border-bottom: 1px solid var(--card-border);
                padding: 1rem 1.25rem;
                font-weight: 700;
            }

            .breadcrumb-item.active {
                color: var(--gold);
            }

            .btn-primary {
                background-color: var(--gold);
                border-color: var(--gold);
                color: var(--slate-dark);
                font-weight: 700;
                border-radius: 0.5rem;
            }

            .btn-primary:hover {
                background-color: var(--gold-light);
                border-color: var(--gold-light);
                color: var(--slate-dark);
                transform: translateY(-1px);
            }

            /* Mesh Background */
            .mesh-background {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
                background: radial-gradient(at 0% 0%, rgba(212, 175, 55, 0.03) 0px, transparent 50%),
                            radial-gradient(at 100% 100%, rgba(212, 175, 55, 0.03) 0px, transparent 50%);
                background-color: var(--slate-dark);
            }

            .animate-up {
                animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            @keyframes slideUp {
                from { opacity: 0; transform: translateY(15px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
        @stack('css')
    </head>
    <body class="sb-nav-fixed antialiased">
        <div class="mesh-background"></div>

        <nav class="sb-topnav navbar navbar-expand navbar-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-4 d-flex align-items-center" href="{{ route('dashboard') }}">
                <div class="bg-white p-1 rounded-2 me-3 shadow-sm" style="width: 32px; height: 32px;">
                    <img src="{{ asset('images/logo-perusahaan.jpeg') }}" alt="Logo" class="w-100 h-100 object-fit-cover rounded-1">
                </div>
                <span class="fw-black tracking-tight text-white uppercase" style="font-size: 0.9rem; letter-spacing: -0.02em;">
                    SILIWANGI<span class="text-gold">ADMIN</span>
                </span>
            </a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0 text-muted" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            
            <!-- Spacer -->
            <div class="ms-auto"></div>

            <!-- Navbar-->
            <ul class="navbar-nav me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=D4AF37&color=0F172A&bold=true" class="rounded-circle" style="width: 28px; height: 28px;" alt="User">
                        <span class="d-none d-md-inline small fw-bold">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-2" aria-labelledby="navbarDropdown" style="background-color: var(--slate-main); min-width: 200px;">
                        <li><div class="dropdown-header text-gold small fw-black text-uppercase">Account Management</div></li>
                        <li><a class="dropdown-item rounded-2 text-white py-2" href="{{ route('profile.edit') }}"><i class="fas fa-cog fa-fw me-2 opacity-50"></i> Profile Settings</a></li>
                        <li><a class="dropdown-item rounded-2 text-white py-2" href="{{ route('home') }}"><i class="fas fa-globe fa-fw me-2 opacity-50"></i> View Site</a></li>
                        <li><hr class="dropdown-divider bg-white opacity-10" /></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-2 text-danger py-2 fw-bold">
                                    <i class="fas fa-power-off fa-fw me-2"></i> Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Primary Analytics</div>
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                                Dashboard
                            </a>
                            
                            @hasanyrole('owner|super-admin|admin|finance')
                            <div class="sb-sidenav-menu-heading">Operations Core</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseBookings" aria-expanded="false" aria-controls="collapseBookings">
                                <div class="sb-nav-link-icon"><i class="fas fa-calendar-check"></i></div>
                                Transactions
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseBookings" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ route('admin.bookings.index') }}">Booking Records</a>
                                    <a class="nav-link" href="{{ route('admin.finance.payments') }}">Payment Gateway</a>
                                </nav>
                            </div>
                            
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCar" aria-expanded="false" aria-controls="collapseCar">
                                <div class="sb-nav-link-icon"><i class="fas fa-car-side"></i></div>
                                Car Assets
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseCar" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ route('admin.cars.index') }}">Vehicle Directory</a>
                                    <a class="nav-link" href="{{ route('admin.car-brands.index') }}">Brand Mapping</a>
                                    <a class="nav-link" href="{{ route('admin.car-types.index') }}">Category Settings</a>
                                </nav>
                            </div>
                            @endhasanyrole

                            @hasanyrole('owner|super-admin|admin|finance|operational')
                            <div class="sb-sidenav-menu-heading">Support & Tracking</div>
                            <a class="nav-link" href="{{ route('admin.schedules.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                                Dispatch Map
                            </a>
                            <a class="nav-link" href="{{ route('admin.maintenances.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div>
                                Asset Health
                            </a>
                            <a class="nav-link" href="{{ route('admin.tracking.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-satellite-dish"></i></div>
                                Active Tracker
                            </a>
                            @endhasanyrole

                            @hasanyrole('owner|super-admin|admin|it-support')
                            <div class="sb-sidenav-menu-heading">Stakeholders</div>
                            <a class="nav-link" href="{{ route('admin.customers.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Customer Base
                            </a>
                            <a class="nav-link" href="{{ route('admin.drivers.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-id-badge"></i></div>
                                Driver Car
                            </a>
                            <a class="nav-link" href="{{ route('admin.users.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>
                                System Operators
                            </a>
                            @endhasanyrole
                        </div>
                    </div>
                    <div class="sb-sidenav-footer border-top border-white border-opacity-10 py-3">
                        <div class="small text-muted mb-1">Operational Role:</div>
                        <div class="fw-bold text-gold small text-uppercase tracking-wider">
                            {{ Auth::user()->roles->pluck('name')->join(' / ') }}
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main class="animate-up">
                    <div class="container-fluid px-4 py-4">
                        @yield('content')
                    </div>
                </main>
                <footer class="py-4 bg-transparent mt-auto border-top border-white border-opacity-5">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted opacity-50">Operational Hub &copy; Siliwangi Rental {{ date('Y') }}</div>
                            <div class="d-flex gap-3">
                                <a href="#" class="text-muted text-decoration-none hover-white">Privacy Architecture</a>
                                <a href="#" class="text-muted text-decoration-none hover-white">Terms of Service</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('sbadmin/js/scripts.js') }}"></script>
        @stack('scripts')
    </body>
</html>
