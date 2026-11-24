<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password | Sukli POS</title>

    <style>
        :root {
            --navy: #15191d;
            --blue: #176bc1;
            --blue-dark: #10599f;
            --yellow: #f3c341;
            --text: #17344f;
            --muted: #6d7f8e;
            --line: #d7e2eb;
            --background: #f1f4f7;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 35px 20px;
            background: var(--background);
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 920px;
        }

        .auth-card {
            display: grid;
            grid-template-columns: 40% 60%;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 15px;
            background: white;
            box-shadow: 0 14px 34px rgba(35, 70, 94, 0.12);
        }

        .auth-intro {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 500px;
            padding: 42px 38px;
            background: var(--navy);
            color: white;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            width: fit-content;
            color: white;
            text-decoration: none;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            color: white;
        }

        .brand-logo svg {
            display: block;
            width: 100%;
            height: 100%;
        }

        .brand-text {
            position: relative;
        }

        .brand-text::after {
            position: absolute;
            left: 0;
            bottom: -9px;
            width: 39px;
            height: 3px;
            border-radius: 10px;
            background: var(--yellow);
            content: "";
        }

        .intro-content {
            max-width: 290px;
        }

        .intro-label,
        .form-label {
            margin: 0 0 10px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .intro-label {
            color: #b9d9f3;
        }

        .intro-content h1 {
            margin: 0 0 14px;
            color: white;
            font-size: 34px;
            line-height: 1.08;
        }

        .intro-content p {
            margin: 0;
            color: #d4dde4;
            font-size: 14px;
            line-height: 1.7;
        }

        .intro-footer {
            padding-top: 22px;
            border-top: 1px solid #3a4248;
            color: #b8c4cd;
            font-size: 11px;
            letter-spacing: 1px;
        }

        .auth-form {
            padding: 58px 58px 48px;
        }

        .form-label {
            color: var(--blue);
        }

        .auth-form h2 {
            margin: 0 0 10px;
            color: #142f49;
            font-size: 30px;
            font-weight: 700;
        }

        .form-description {
            max-width: 470px;
            margin: 0 0 30px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .status-message,
        .error-message {
            margin-bottom: 18px;
            padding: 11px 13px;
            border-radius: 7px;
            font-size: 13px;
        }

        .status-message {
            border: 1px solid #b8e3c9;
            background: #ecfaf1;
            color: #277246;
        }

        .error-message {
            border: 1px solid #e4b8b8;
            background: #fff2f2;
            color: #a02d2d;
        }

        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            color: #203a52;
            font-size: 13px;
            font-weight: 700;
        }

        .field input {
            width: 100%;
            height: 45px;
            padding: 0 13px;
            border: 1px solid #bfd0de;
            border-radius: 7px;
            outline: none;
            color: #17344f;
            font-size: 14px;
        }

        .field input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(23, 107, 193, 0.13);
        }

        .input-error {
            margin: 7px 0 0;
            color: #b12e2e;
            font-size: 12px;
        }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-top: 26px;
        }

        .primary-button {
            min-height: 43px;
            padding: 0 18px;
            border: 0;
            border-radius: 7px;
            background: var(--blue);
            color: white;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .primary-button:hover {
            background: var(--blue-dark);
        }

        .back-link {
            color: var(--blue);
            font-size: 13px;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .page-footer {
            margin-top: 16px;
            color: #81909d;
            font-size: 12px;
            text-align: center;
        }

        @media (max-width: 700px) {
            .auth-card {
                grid-template-columns: 1fr;
            }

            .auth-intro {
                min-height: auto;
                gap: 48px;
                padding: 30px;
            }

            .auth-form {
                padding: 36px 28px;
            }

            .auth-form h2 {
                font-size: 26px;
            }

            .form-actions {
                align-items: flex-start;
                flex-direction: column-reverse;
            }

            .primary-button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <main class="auth-wrapper">
        <section class="auth-card">
            <aside class="auth-intro">

                <a href="{{ url('/') }}" class="brand">
                    <span class="brand-logo" aria-hidden="true">
                        <svg viewBox="0 0 48 48" role="img">
                            <path
                                d="M8 18h32v23H8z"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="3"
                            />
                            <path
                                d="M6 18h36L38 8H10L6 18z"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="3"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M16 25v16M32 25v16"
                                stroke="currentColor"
                                stroke-width="3"
                            />
                            <path
                                d="M15 13h18"
                                stroke="#f3c341"
                                stroke-width="3"
                                stroke-linecap="round"
                            />
                        </svg>
                    </span>

                    <span class="brand-text">SUKLI POS</span>
                </a>

                <div class="intro-content">
                    <p class="intro-label">STORE STAFF ACCESS</p>

                    <h1>
                        Sales and stock,<br>
                        in one place.
                    </h1>

                    <p>
                        Manage products, customers, and daily sales
                        from your store counter.
                    </p>
                </div>

                <div class="intro-footer">
                    PRODUCTS &nbsp;·&nbsp; CUSTOMERS &nbsp;·&nbsp; SALES
                </div>
            </aside>

            <section class="auth-form">
                <p class="form-label">ACCOUNT RECOVERY</p>

                <h2>Forgot your password?</h2>

                <p class="form-description">
                    Enter the email address connected to your account.
                    If it is registered, we will send you a password reset link.
                </p>

                @if (session('status'))
                    <div class="status-message">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="error-message">
                        Please check the email address and try again.
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="field">
                        <label for="email">Email address</label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="you@example.com"
                        >

                        @error('email')
                            <p class="input-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('login') }}" class="back-link">
                            Back to log in
                        </a>

                        <button type="submit" class="primary-button">
                            Send reset link
                        </button>
                    </div>
                </form>
            </section>
        </section>

        <p class="page-footer">
            Sukli POS — Store management
        </p>
    </main>
</body>
</html>