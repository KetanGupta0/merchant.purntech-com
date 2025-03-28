<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Merchant Onboarding</title>
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2@11.js') }}"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
        * {
            font-family: "Unbounded", serif;
            font-optical-sizing: auto;
            font-weight: 200;
            font-style: normal;
        }

        .btn-primary {
            --bs-btn-color: #fff;
            --bs-btn-bg: #eb5d1e;
            --bs-btn-border-color: #eb5d1e;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #9b4019;
            --bs-btn-hover-border-color: #9b4019;
            --bs-btn-focus-shadow-rgb: 49, 132, 253;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #9b4019;
            --bs-btn-active-border-color: #9b4019;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            --bs-btn-disabled-color: #fff;
            --bs-btn-disabled-bg: #eb5d1e;
            --bs-btn-disabled-border-color: #eb5d1e;
            font-size: 12px
        }

        .custom-nav {
            border: 1px solid #eb5d1e;
            border-radius: 45px;
            padding: 5px 20px;
            background: #000;
        }

        .custom-nav .nav-link {
            color: #fff !important;
            padding-left: 20px;
            padding-right: 20px;
            padding-top: 8px;
            padding-bottom: 8px;
            font-size: 12px;
            font-weight: 300
        }

        .custom-nav .nav-link.active {
            color: #eb5d1e !important;
            background-color: #262626;
            border-radius: 45px;
            padding-left: 20px;
            padding-right: 20px;
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .bg-body-tertiary {
            background: transparent !important;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 15;
        }


        .contact-form input,
        .contact-form textarea {
            background: #545454 !important;
            color: #ffffff !important;
            border: 0 !important;
            outline: none !important;
            box-shadow: none !important;
            padding: 15px 10px !important;
            border-radius: 20px !important;
        }

        .contact-form .btn {
            padding-top: 14px !important;
            padding-bottom: 14px !important;
            border-radius: 20px !important;
        }

        .footer-menu .nav-menu {
            list-style: none;
            display: flex;
            gap: 12px;
            padding: 0;
            justify-content: center;
            flex-wrap: wrap;
        }

        .footer-menu .nav-menu li a {
            text-decoration: none;
            color: white;
            padding: 10px 0px;
            transition: color 0.3s ease-in-out;
        }

        .footer-menu .nav-menu li a.active {
            color: #eb5d1e;
        }

        .footer-menu .nav-menu li a:hover {
            color: #f8f9fa;
        }

        .newsletter input {
            background: #545454 !important;
            color: #ffffff !important;
            border: 0 !important;
            outline: none !important;
            box-shadow: none !important;
            padding: 10px !important;
            border-radius: 20px !important;
        }

        .newsletter .btn {
            padding: 12px !important;
            border-radius: 20px !important;
            border: 0 !important;
            outline: none !important;
            box-shadow: none !important;
        }
        .talk {
            margin-top:-67px;
        }

        @media (max-width:767px) {
            .bg-body-tertiary {
                background: #000000 !important;
            }

            .talk {
                margin-top:0px;
            }

            .footer-menu .nav-menu {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
        }

        :root {
            --token-2f560859-5998-4075-847c-9f666c5cfc0b: rgb(10, 10, 10);
        }

        .framer-ne3cqe-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .main {
            width: 100%;
            height: 100%;
            background-color: rgb(10, 10, 10);
        }

        .my-img {
            background-size: 100%;
            background-image: url('{{ asset('/assets/images/top-bg.png') }}');
            height: 100%;
            width: 100%;
            background-repeat: no-repeat;
        }

        .font-12 {
            font-size: 12px !important;
        }

        .navbar-toggler {
            padding: 2px !important;
            background-color: #eb5d1e !important;
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #0A0A0A;
        }

        ::-webkit-scrollbar-thumb {
            background: #eb5d1e;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #c44b17;
        }
    </style>
</head>

<body>
    <div class="framer-ne3cqe-container">
        <div class="main">
            <div class="my-img"></div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="{{ url('/') }}">
                <img src="{{ asset('/assets/images/PurnTechLogo.png') }}" style="width:150px" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 custom-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" aria-current="page"
                            href="{{ url('/') }}">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" aria-current="page"
                            href="{{ url('/about') }}">ABOUT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('resources') ? 'active' : '' }}" aria-current="page"
                            href="{{ url('/resources') }}">RESOURCES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('merchants') ? 'active' : '' }}" aria-current="page"
                            href="{{ url('/merchants') }}">MERCHANTS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('payments') ? 'active' : '' }}" aria-current="page"
                            href="{{ url('/payments') }}">PAYMENTS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('merchant/onboarding') ? 'active' : '' }}"
                            aria-current="page" href="{{ url('/merchant/onboarding') }}">ONBOARDING</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('login') ? 'active' : '' }}" aria-current="page"
                            href="{{ url('/login') }}">LOGIN</a>
                    </li>
                </ul>
                <div>
                    <a href="{{ url('/contact-us') }}" class="btn btn-primary rounded-pill px-3"
                        style="font-size:12px">CONTACT</a>
                </div>
            </div>
        </div>
    </nav>
