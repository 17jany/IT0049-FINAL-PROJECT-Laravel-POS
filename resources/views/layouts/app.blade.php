<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Sukli POS') | Sukli POS</title>

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --black: #17191c;
            --background: #f1f2f5;
            --white: #ffffff;
            --text: #252525;
            --muted: #626b75;
            --border: #e3e3e3;
            --blue: #1769c2;
            --blue-hover: #1057a4;
        }

        body {
            margin: 0;
            color: var(--text);
            background: var(--background);
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
        }

        .site-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            min-height: 58px;
            padding: 0 max(4%, calc((100% - 1200px) / 2));
            background: var(--black);
        }

        .site-nav .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-right: auto;
            padding: 8px 0;
            color: var(--white);
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-decoration: none;
            white-space: nowrap;
        }

        .site-nav .brand svg {
            width: 30px;
            height: 30px;
            flex: 0 0 auto;
        }

        .site-nav .brand-name::after {
            content: "";
            display: block;
            width: 48px;
            height: 3px;
            margin-top: 2px;
            background: #f2c94c;
            border-radius: 4px;
        }

        .site-nav .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .site-nav .nav-link,
        .site-nav .account-menu summary {
            display: block;
            padding: 9px 12px;
            color: #eeeeee;
            border: 0;
            border-radius: 6px;
            font-size: 13px;
            text-decoration: none;
            white-space: nowrap;
            cursor: pointer;
        }

        .site-nav .nav-link:hover,
        .site-nav .nav-link.active {
            color: var(--white);
            background: var(--blue);
        }

        .site-nav .account-menu {
            position: relative;
            margin-left: 8px;
        }

        .site-nav .account-menu summary {
            list-style: none;
        }

        .site-nav .account-menu summary::-webkit-details-marker {
            display: none;
        }

        .site-nav .account-menu summary:hover,
        .site-nav .account-menu[open] summary {
            color: var(--white);
            background: #30343a;
        }

        .site-nav .account-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            z-index: 10;
            min-width: 170px;
            padding: 6px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 7px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .site-nav .account-dropdown a,
        .site-nav .account-dropdown button {
            display: block;
            width: 100%;
            padding: 9px 10px;
            color: var(--text);
            background: transparent;
            border: 0;
            border-radius: 5px;
            font: inherit;
            font-size: 13px;
            text-align: left;
            text-decoration: none;
            cursor: pointer;
        }

        .site-nav .account-dropdown a:hover,
        .site-nav .account-dropdown button:hover {
            background: var(--background);
        }

        .site-nav .account-dropdown form {
            margin: 0;
        }

        main {
            width: min(1100px, 92%);
            min-height: calc(100vh - 110px);
            margin: 22px auto;
        }

        h1,
        h2,
        p {
            overflow-wrap: anywhere;
        }

        .pos-content {
            width: 100%;
            margin: 24px auto;
        }

        .pos-hero {
            padding: 22px 24px;
            background: var(--white);
            border-left: 3px solid var(--black);
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .pos-eyebrow,
        .eyebrow {
            margin: 0 0 8px;
            color: #555555;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1.1px;
            text-transform: uppercase;
        }

        .pos-hero h1,
        .page-title {
            margin: 0 0 10px;
            color: var(--text);
            font-size: 32px;
            line-height: 1.2;
        }

        .pos-hero p:not(.pos-eyebrow),
        .subtitle {
            margin: 0;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.6;
        }

        .pos-button,
        .button {
            display: inline-block;
            padding: 10px 14px;
            color: var(--white);
            background: var(--black);
            border: 1px solid var(--black);
            border-radius: 7px;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
        }

        .pos-button {
            margin-top: 16px;
        }

        .pos-button:hover,
        .button:hover {
            color: var(--white);
            background: #404040;
        }

        .pos-cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 14px;
        }

        .pos-card {
            padding: 16px;
            background: var(--white);
            border: 1px solid #eeeeee;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .pos-card h2 {
            margin: 0 0 7px;
            color: var(--text);
            font-size: 16px;
        }

        .pos-card p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.55;
        }

        .table-section,
        .form-section {
            padding: 24px;
            overflow-x: auto;
            background: var(--white);
            border: 1px solid #e6e6e6;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .table-section h1,
        .form-section h1 {
            margin: 0 0 10px;
            font-size: 30px;
        }

        .record-count {
            display: inline-block;
            margin: 16px 0 0;
            padding: 7px 10px;
            color: #404040;
            background: #eeeeee;
            border-left: 3px solid var(--black);
            border-radius: 6px;
            font-size: 13px;
        }

        .action-row {
            margin: 16px 0;
        }

        table {
            width: 100%;
            min-width: 600px;
            margin-top: 14px;
            border-collapse: collapse;
            font-size: 14px;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        th {
            color: var(--white);
            background: var(--black);
            font-size: 13px;
        }

        tbody tr:hover {
            background: #f7f7f7;
        }

        .avatar-image {
            width: 46px;
            height: 46px;
            object-fit: cover;
            border-radius: 50%;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-top: 22px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-size: 15px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 11px 12px;
            color: var(--text);
            background: var(--white);
            border: 1px solid #cccccc;
            border-radius: 7px;
            font: inherit;
            font-size: 15px;
        }

        .form-group input:focus {
            border-color: #555555;
            outline: 2px solid #dddddd;
        }

        .form-error {
            margin: 0;
            color: #b42318;
            font-size: 13px;
        }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 22px;
        }

        .button-secondary {
            color: var(--text);
            background: #eeeeee;
            border-color: #dddddd;
        }

        .button-secondary:hover {
            color: var(--text);
            background: #dddddd;
        }

        .success-message {
            margin: 14px 0;
            padding: 11px 13px;
            color: #17663a;
            background: #e9f7ef;
            border: 1px solid #b8e3c8;
            border-radius: 7px;
            font-size: 14px;
        }

        footer {
            padding: 18px;
            color: #777777;
            text-align: center;
            font-size: 13px;
        }

        @media (max-width: 650px) {
            .site-nav {
                flex-wrap: wrap;
                gap: 4px;
                padding: 8px 4%;
            }

            .site-nav .nav-links {
                order: 3;
                width: 100%;
                overflow-x: auto;
            }

            .site-nav .account-menu {
                margin-left: 0;
            }

            main {
                width: 92%;
                margin: 18px auto;
            }

            .pos-cards,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: auto;
            }

            .table-section,
            .form-section {
                padding: 18px;
            }

            .pos-hero h1,
            .table-section h1,
            .form-section h1 {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>
    <nav class="site-nav">
        <a
            class="brand"
            href="{{ auth()->check() ? route('dashboard') : route('home') }}"
            aria-label="Sukli POS"
        >
            <svg viewBox="0 0 48 48" fill="none" aria-hidden="true">
                <path d="M7 19h34l-4-11H11L7 19Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
                <path d="M10 19v21h28V19M18 40V28h12v12" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
                <path d="M16 8v8m8-8v8m8-8v8" stroke="currentColor" stroke-width="2.5"/>
            </svg>
            <span class="brand-name">SUKLI POS</span>
        </a>

        @auth
            <div class="nav-links">
                <a
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}"
                >
                    Dashboard
                </a>

                @if (\Illuminate\Support\Facades\Route::has('sales.index'))
                    <a
                        class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}"
                        href="{{ route('sales.index') }}"
                    >
                        Sales History
                    </a>
                @endif

                @if (\Illuminate\Support\Facades\Route::has('products.index'))
                    <a
                        class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                        href="{{ route('products.index') }}"
                    >
                        Products
                    </a>
                @endif

                @if (\Illuminate\Support\Facades\Route::has('customers.index'))
                    <a
                        class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"
                        href="{{ route('customers.index') }}"
                    >
                        Customers
                    </a>
                @endif

                @if (\Illuminate\Support\Facades\Route::has('users.index'))
                    <a
                        class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                        href="{{ route('users.index') }}"
                    >
                        Staff
                    </a>
                @endif
            </div>

            <details class="account-menu">
                <summary>
                    {{ Auth::user()->full_name ?? Auth::user()->username ?? Auth::user()->name ?? 'Account' }} ▾
                </summary>

                <div class="account-dropdown">
                    @if (\Illuminate\Support\Facades\Route::has('profile.edit'))
                        <a href="{{ route('profile.edit') }}">My Profile</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Log out</button>
                    </form>
                </div>
            </details>
        @else
            <div class="nav-links">
                <a class="nav-link" href="{{ route('login') }}">Log in</a>

                @if (\Illuminate\Support\Facades\Route::has('register'))
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                @endif
            </div>
        @endauth
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        Sukli POS
    </footer>
</body>
</html>