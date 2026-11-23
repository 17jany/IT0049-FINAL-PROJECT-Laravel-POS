<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sukli POS') }} | Staff Access</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Figtree, Arial, sans-serif;
            color: #202124;
        }

        .auth-page {
            min-height: 100vh;
            padding: 32px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #eef0f3;
        }

        .auth-card {
            width: min(960px, 100%);
            display: grid;
            grid-template-columns: 0.85fr 1.15fr;
            overflow: hidden;
            background: #fff;
            border: 1px solid #e0e3e7;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(20, 25, 30, 0.12);
        }

        .auth-brand {
            display: flex;
            flex-direction: column;
            padding: 38px;
            color: #fff;
            background: #17191c;
        }

        .auth-logo {
            display: inline-flex;
            align-items: center;
            gap: 11px;
            width: fit-content;
            color: #fff;
            text-decoration: none;
        }

        .auth-logo svg {
            width: 35px;
            height: 35px;
            flex: 0 0 auto;
        }

        .auth-logo-name {
            font-size: 21px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .auth-logo-name::after {
            content: "";
            display: block;
            width: 56px;
            height: 3px;
            margin-top: 3px;
            background: #f2c94c;
            border-radius: 5px;
        }

        .auth-brand-copy {
            margin-top: auto;
            padding-top: 70px;
        }

        .auth-brand-copy .auth-brand-label {
            margin: 0 0 10px;
            color: #b8c0ca;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .auth-brand-copy h2 {
            margin: 0 0 12px;
            font-size: 30px;
            line-height: 1.15;
        }

        .auth-brand-copy p:last-child {
            max-width: 290px;
            margin: 0;
            color: #c9cdd2;
            font-size: 14px;
            line-height: 1.6;
        }

        .auth-brand-note {
            margin-top: 34px;
            padding-top: 16px;
            color: #aeb4bc;
            border-top: 1px solid #3a3d41;
            font-size: 12px;
            letter-spacing: 0.3px;
        }

        .auth-panel {
            min-width: 0;
            padding: 42px clamp(26px, 5vw, 54px);
            background: #fff;
        }

        .auth-content {
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
        }

        .auth-eyebrow {
            margin: 0 0 8px;
            color: #1769c2;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.1px;
            text-transform: uppercase;
        }

        .auth-heading {
            margin: 0;
            color: #17191c;
            font-size: 30px;
            line-height: 1.2;
        }

        .auth-description {
            margin: 8px 0 0;
            color: #69717b;
            font-size: 14px;
            line-height: 1.5;
        }

        .auth-form {
            display: grid;
            gap: 17px;
            margin-top: 25px;
        }

        .auth-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .auth-field {
            min-width: 0;
        }

        .auth-grid-span {
            grid-column: 1 / -1;
        }

        .auth-card .auth-panel label.auth-label {
            display: block;
            color: #343a40;
            font-size: 13px;
            font-weight: 600;
        }

        .auth-card .auth-panel input.auth-input {
            display: block;
            width: 100%;
            margin-top: 7px;
            padding: 11px 12px;
            color: #202124;
            background: #fff;
            border: 1px solid #cbd0d6;
            border-radius: 6px;
            font: inherit;
            font-size: 14px;
            box-shadow: none;
        }

        .auth-card .auth-panel input.auth-input:focus {
            border-color: #1769c2;
            outline: 3px solid rgba(23, 105, 194, 0.13);
        }

        .auth-card .auth-panel input.auth-checkbox {
            width: 16px;
            height: 16px;
            accent-color: #1769c2;
        }

        .auth-error {
            margin-top: 6px;
            color: #b42318;
            font-size: 12px;
        }

        .auth-links-row,
        .auth-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .auth-remember {
            display: inline-flex !important;
            align-items: center;
            gap: 8px;
            color: #5f6670 !important;
            font-size: 13px !important;
            font-weight: 400 !important;
        }

        .auth-link {
            color: #1769c2;
            font-size: 13px;
            text-decoration: none;
        }

        .auth-link:hover {
            color: #104f94;
            text-decoration: underline;
        }

        .auth-card .auth-panel button.auth-submit {
            min-height: 42px;
            padding: 10px 18px;
            color: #fff;
            background: #1769c2;
            border: 1px solid #1769c2;
            border-radius: 6px;
            font: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: none;
        }

        .auth-card .auth-panel button.auth-submit:hover {
            color: #fff;
            background: #1057a4;
            border-color: #1057a4;
        }

        .auth-switch {
            margin: 22px 0 0;
            padding-top: 16px;
            color: #69717b;
            border-top: 1px solid #e7e9ec;
            font-size: 13px;
            text-align: center;
        }

        .auth-status {
            margin-top: 18px;
            color: #17663a;
            font-size: 13px;
        }

        .auth-footer {
            margin-top: 17px;
            color: #7b828b;
            font-size: 12px;
            text-align: center;
        }

        @media (max-width: 720px) {
            .auth-page {
                padding: 16px;
            }

            .auth-card {
                max-width: 520px;
                grid-template-columns: 1fr;
            }

            .auth-brand {
                padding: 22px 25px;
            }

            .auth-brand-copy {
                padding-top: 25px;
            }

            .auth-brand-copy h2 {
                font-size: 23px;
            }

            .auth-brand-copy p:last-child,
            .auth-brand-note {
                display: none;
            }

            .auth-panel {
                padding: 28px 25px;
            }
        }

        @media (max-width: 480px) {
            .auth-form-grid {
                grid-template-columns: 1fr;
            }

            .auth-grid-span {
                grid-column: auto;
            }

            .auth-heading {
                font-size: 26px;
            }

            .auth-links-row {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="auth-page">
        <div class="auth-card">
            <aside class="auth-brand">
                <a class="auth-logo" href="{{ url('/') }}" aria-label="Sukli POS home">
                    <svg viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <path d="M7 19h34l-4-11H11L7 19Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
                        <path d="M10 19v21h28V19M18 40V28h12v12" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
                        <path d="M16 8v8m8-8v8m8-8v8" stroke="currentColor" stroke-width="2.5"/>
                    </svg>
                    <span class="auth-logo-name">SUKLI POS</span>
                </a>

                <div class="auth-brand-copy">
                    <p class="auth-brand-label">Store staff access</p>
                    <h2>Sales and stock,<br>in one place.</h2>
                    <p>Manage products, customers, and daily sales from your store counter.</p>
                </div>

                <div class="auth-brand-note">
                    PRODUCTS &nbsp;·&nbsp; CUSTOMERS &nbsp;·&nbsp; SALES
                </div>
            </aside>

            <main class="auth-panel">
                {{ $slot }}
            </main>
        </div>

        <footer class="auth-footer">
            Sukli POS &mdash; Store management
        </footer>
    </div>
</body>
</html>