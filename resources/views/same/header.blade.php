<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if (Session::has('userType'))
            @if (Session::get('userType') == 'Merchant')
                Merchant Dashboard
            @elseif(Session::get('userType') == 'Super Admin' || Session::get('userType') == 'Admin')
                Admin Dashboard
            @elseif(Session::get('userType') == 'Agent')
                Agent Dashboard
            @else
                Dashboard
            @endif
        @else
            Dashboard
        @endif
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin dashboard" name="description" />
    <meta content="PurnTech" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">

    <!-- plugin css -->
    <link href="{{ asset('assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    {{-- <link href="{{ asset('/css/dataTables.dataTables.css') }}" rel="stylesheet" type="text/css" /> --}}
    <link href="{{ asset('/css/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('/js/dataTables.js') }}"></script>
    <script src="{{ asset('/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2@11.js') }}"></script>

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box horizontal-logo">
                            <a href="{{ url('/') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('favicon.svg') }}" alt="" height="50">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('logo.svg') }}" alt="" height="50">
                                </span>
                            </a>

                            <a href="{{ url('/') }}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset('favicon.svg') }}" alt="" height="50">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('logo.svg') }}" alt="" height="50">
                                </span>
                            </a>
                        </div>

                        <button type="button"
                            class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                            id="topnav-hamburger-icon">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                data-toggle="fullscreen">
                                <i class='bx bx-fullscreen fs-22'></i>
                            </button>
                        </div>

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                                <i class='bx bx-moon fs-22'></i>
                            </button>
                        </div>



                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    @if (Session::has('userType'))
                                        @if (Session::get('userType') == 'Admin' || Session::get('userType') == 'Super Admin')
                                            @if (Session::get('userPic'))
                                                <img class="rounded-circle header-profile-user"
                                                    src="{{ asset('uploads/admin/profile') }}/{{ Session::get('userPic') }}"
                                                    alt="Header Avatar">
                                            @else
                                                <img class="rounded-circle header-profile-user"
                                                    src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                                                    alt="Header Avatar">
                                            @endif
                                        @elseif (Session::get('userType') == 'Merchant')
                                            @if (Session::get('userPic'))
                                                <img class="rounded-circle header-profile-user"
                                                    src="{{ asset('uploads/merchant/profile') }}/{{ Session::get('userPic') }}"
                                                    alt="Header Avatar">
                                            @else
                                                <img class="rounded-circle header-profile-user"
                                                    src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                                                    alt="Header Avatar">
                                            @endif
                                        @elseif (Session::get('userType') == 'Agent')
                                            @if (Session::get('userPic'))
                                                <img class="rounded-circle header-profile-user"
                                                    src="{{ asset('uploads/agent/profile') }}/{{ Session::get('userPic') }}"
                                                    alt="Header Avatar">
                                            @else
                                                <img class="rounded-circle header-profile-user"
                                                    src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                                                    alt="Header Avatar">
                                            @endif
                                        @endif
                                    @endif
                                    <span class="text-start ms-xl-2">
                                        <span
                                            class="d-none d-xl-inline-block ms-1 fw-medium user-name-text text-capitalize">{{ Session::get('userName') }}</span>
                                        <span
                                            class="d-none d-xl-block ms-1 fs-12 user-name-sub-text text-capitalize">{{ Session::get('userType') }}</span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <h6 class="dropdown-header">Welcome {{ Session::get('userName') }}!</h6>
                                @if (Session::has('userType'))
                                    @if (Session::get('userType') == 'Admin' || Session::get('userType') == 'Super Admin')
                                        <a class="dropdown-item" href="{{ url('admin/settings') }}">
                                            <span
                                                class="badge bg-success-subtle text-success mt-1 float-end">New</span>
                                            <i class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i>
                                            <span class="align-middle">Settings</span>
                                        </a>
                                        <a class="dropdown-item" href="{{ url('logout') }}">
                                            <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                                            <span class="align-middle" data-key="t-logout">Logout</span>
                                        </a>
                                    @elseif(Session::get('userType') === 'Merchant')
                                        @isset($balance)
                                            <a class="dropdown-item" href="#">
                                                <i class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i>
                                                <span class="align-middle">Balance :
                                                    <b>₹{{ sprintf('%.2f', $balance) }}</b></span>
                                            </a>
                                        @endisset
                                        <a class="dropdown-item" href="{{ url('merchant/settings') }}">
                                            <span
                                                class="badge bg-success-subtle text-success mt-1 float-end">New</span>
                                            <i class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i>
                                            <span class="align-middle">Settings</span>
                                        </a>
                                        <a class="dropdown-item" href="{{ url('logout') }}">
                                            <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                                            <span class="align-middle" data-key="t-logout">Logout</span>
                                        </a>
                                    @else
                                        @isset($balance)
                                            <a class="dropdown-item" href="#">
                                                <i class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i>
                                                <span class="align-middle">Balance :
                                                    <b>₹{{ sprintf('%.2f', $balance) }}</b></span>
                                            </a>
                                        @endisset
                                        <a class="dropdown-item" href="{{ url('agent/settings') }}">
                                            <span
                                                class="badge bg-success-subtle text-success mt-1 float-end">New</span>
                                            <i class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i>
                                            <span class="align-middle">Settings</span>
                                        </a>
                                        <a class="dropdown-item" href="{{ url('logout') }}">
                                            <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                                            <span class="align-middle" data-key="t-logout">Logout</span>
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="{{ url('/') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('favicon.svg') }}" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('logo.svg') }}" alt="" height="50">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="{{ url('/') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('favicon.svg') }}" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('logo.svg') }}" alt="" height="50">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">

                    <div id="two-column-menu">
                    </div>
                    @if (Session::has('userType'))
                        @if (Session::get('userType') === 'Super Admin' || Session::get('userType') === 'Admin')
                            <ul class="navbar-nav" id="navbar-nav">
                                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link dashboard  {{ Request::is('admin/dashboard') ? 'active' : '' }}"
                                        href="{{ url('admin/dashboard') }}">
                                        <i class="mdi mdi-monitor-dashboard"></i> <span
                                            data-key="t-widgets">Dashboard</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('admin/merchant/approval') ? 'active' : '' }}"
                                        href="{{ url('admin/merchant/approval') }}">
                                        <i class="ri-store-line"></i> <span
                                            data-key="t-widgets">Merchants</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('admin/agents') ? 'active' : '' }}"
                                        href="{{ url('admin/agents') }}">
                                        <i class="mdi mdi-account-check"></i> <span data-key="t-widgets">Agents</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('admin/account/details') ? 'active' : '' }}"
                                        href="{{ url('admin/account/details') }}">
                                        <i class="mdi mdi-bank-check"></i> <span data-key="t-widgets">Account
                                            Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('admin/url/whitelisting') ? 'active' : '' }}"
                                        href="{{ url('admin/url/whitelisting') }}">
                                        <i class="mdi mdi-web-check"></i> <span data-key="t-widgets">URL
                                            Whitelisting</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('admin/load-wallet') ? 'active' : '' }}"
                                        href="{{ url('/admin/load-wallet') }}">
                                        <i class="ri-wallet-3-line"></i> <span data-key="t-widgets">Load Wallet</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('admin/bulk-payout') ? 'active' : '' }}"
                                        href="{{ url('/admin/bulk-payout') }}">
                                        <i class="ri-exchange-line"></i> <span data-key="t-widgets">Bulk Payout</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('admin/settlement/report') ? 'active' : '' }}"
                                        href="{{ url('/admin/settlement/report') }}">
                                        <i class="ri-file-text-line"></i> <span data-key="t-widgets">Settlement
                                            Report</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('admin/transaction') ? 'active' : '' }}"
                                        href="{{ url('/admin/transaction') }}">
                                        <i class="ri-swap-box-line"></i> <span data-key="t-widgets">Transactions</span>
                                    </a>
                                </li>
                                <li class="menu-title"><i class="ri-more-fill"></i> <span
                                        data-key="t-components">Other</span></li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('admin/settings') ? 'active' : '' }}"
                                        href="{{ url('admin/settings') }}">
                                        <i class="ri-settings-5-line"></i> <span data-key="t-widgets">Settings</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('admin/logs') ? 'active' : '' }}"
                                        href="{{ url('admin/logs') }}">
                                        <i class="bx bx-notepad"></i> <span data-key="t-widgets">Logs</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="{{ url('logout') }}">
                                        <i class="bx bx-power-off"></i> <span data-key="t-widgets">Logout</span>
                                    </a>
                                </li>
                            </ul>
                        @elseif(Session::get('userType') === 'Merchant')
                            <ul class="navbar-nav" id="navbar-nav">
                                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link dashboard  {{ Request::is('merchant/dashboard') ? 'active' : '' }}"
                                        href="{{ url('merchant/dashboard') }}">
                                        <i class="mdi mdi-monitor-dashboard"></i> <span
                                            data-key="t-widgets">Dashboard</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('merchant/account/details') ? 'active' : '' }}"
                                        href="{{ url('merchant/account/details') }}">
                                        <i class="mdi mdi-bank-check"></i> <span data-key="t-widgets">Account
                                            Details</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('merchant/url/whitelisting') ? 'active' : '' }}"
                                        href="{{ url('merchant/url/whitelisting') }}">
                                        <i class="mdi mdi-web-check"></i> <span data-key="t-widgets">URL
                                            Whitelisting</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('merchant/fund-request') ? 'active' : '' }}"
                                        href="{{ url('/merchant/fund-request') }}">
                                        <i class="ri-money-dollar-circle-line"></i> <span data-key="t-widgets">Fund Request</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('merchant/settlement/report') ? 'active' : '' }}"
                                        href="{{ url('/merchant/settlement/report') }}">
                                        <i class="ri-file-text-line"></i> <span data-key="t-widgets">Settlement
                                            Report</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('merchant/transaction') ? 'active' : '' }}"
                                        href="{{ url('/merchant/transaction') }}">
                                        <i class="ri-swap-box-line"></i> <span data-key="t-widgets">Transactions</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('merchant/payin') ? 'active' : '' }}"
                                        href="{{ url('/merchant/payin') }}">
                                        <i class="ri-wallet-line"></i> <span data-key="t-widgets">Payin</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('merchant/payout') ? 'active' : '' }}"
                                        href="{{ url('/merchant/payout') }}">
                                        <i class="ri-exchange-line"></i> <span data-key="t-widgets">Payout</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('merchant/beneficiaries') ? 'active' : '' }}"
                                        href="{{ url('/merchant/beneficiaries') }}">
                                        <i class="ri-team-line"></i> <span data-key="t-widgets">Beneficiaries</span>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                        <a class="nav-link menu-link  {{ Request::is('merchant/topup-details') ? 'active' : '' }}" href="{{url('/merchant/topup-details')}}">
                                            <i class="bx bx-notepad"></i> <span data-key="t-widgets">TopUp Details</span>
                                        </a>
                                    </li> --}}
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('merchant/developer-section') ? 'active' : '' }}"
                                        href="{{ url('/merchant/developer-section') }}">
                                        <i class="ri-code-line"></i> <span data-key="t-widgets">Developer
                                            Section</span>
                                    </a>
                                </li>
                                <li class="menu-title"><i class="ri-more-fill"></i> <span
                                        data-key="t-components">Other</span></li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('merchant/settings') ? 'active' : '' }}"
                                        href="{{ url('merchant/settings') }}">
                                        <i class="ri-settings-5-line"></i> <span data-key="t-widgets">Settings</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('merchant/logs') ? 'active' : '' }}"
                                        href="{{ url('merchant/logs') }}">
                                        <i class="bx bx-notepad"></i> <span data-key="t-widgets">Activity Log</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="{{ url('logout') }}">
                                        <i class="bx bx-power-off"></i> <span data-key="t-widgets">Logout</span>
                                    </a>
                                </li>
                            </ul>
                        @else
                            <ul class="navbar-nav" id="navbar-nav">
                                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link dashboard  {{ Request::is('agent/dashboard') ? 'active' : '' }}"
                                        href="{{ url('agent/dashboard') }}">
                                        <i class="mdi mdi-monitor-dashboard"></i> <span
                                            data-key="t-widgets">Dashboard</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link dashboard  {{ Request::is('agent/account') ? 'active' : '' }}"
                                        href="{{ url('agent/account') }}">
                                        <i class="mdi mdi-bank-check"></i> <span data-key="t-widgets">Account</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link dashboard  {{ Request::is('agent/merchants') ? 'active' : '' }}"
                                        href="{{ url('agent/merchants') }}">
                                        <i class="ri-store-line"></i> <span data-key="t-widgets">Merchants</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link dashboard  {{ Request::is('agent/payin-merchant') ? 'active' : '' }}"
                                        href="{{ url('agent/payin-merchant') }}">
                                        <i class="ri-wallet-line"></i> <span data-key="t-widgets">PayIn Reports</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link dashboard  {{ Request::is('agent/payout-merchant') ? 'active' : '' }}"
                                        href="{{ url('agent/payout-merchant') }}">
                                        <i class="ri-exchange-line"></i> <span data-key="t-widgets">PayOut
                                            Reports</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link dashboard  {{ Request::is('agent/payout') ? 'active' : '' }}"
                                        href="{{ url('agent/payout') }}">
                                        <i class="ri-exchange-line"></i> <span data-key="t-widgets">PayOut
                                            (Agent)</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link dashboard  {{ Request::is('agent/beneficiaries') ? 'active' : '' }}"
                                        href="{{ url('agent/beneficiaries') }}">
                                        <i class="ri-team-line"></i> <span data-key="t-widgets">Beneficiaries</span>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a class="nav-link menu-link dashboard  {{ Request::is('agent/merchant/transactions') ? 'active' : '' }}" href="{{url('agent/merchant/transactions')}}">
                                        <i class="mdi mdi-monitor-dashboard"></i> <span data-key="t-widgets">Merchant Transactions</span>
                                    </a>
                                </li> --}}
                                <li class="menu-title"><i class="ri-more-fill"></i> <span
                                        data-key="t-components">Other</span></li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('agent/settings') ? 'active' : '' }}"
                                        href="{{ url('agent/settings') }}">
                                        <i class="ri-settings-5-line"></i> <span data-key="t-widgets">Settings</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link  {{ Request::is('agent/logs') ? 'active' : '' }}"
                                        href="{{ url('agent/logs') }}">
                                        <i class="bx bx-notepad"></i> <span data-key="t-widgets">Activity Log</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link" href="{{ url('logout') }}">
                                        <i class="bx bx-power-off"></i> <span data-key="t-widgets">Logout</span>
                                    </a>
                                </li>
                            </ul>
                        @endif
                    @endif
                </div>
                <!-- Sidebar -->
            </div>

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
