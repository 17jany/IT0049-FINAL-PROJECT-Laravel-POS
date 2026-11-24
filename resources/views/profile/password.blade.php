@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
    <style>
        .password-page {
            width: 100%;
            max-width: 900px;
            margin: 24px auto;
        }

        .password-breadcrumb {
            margin: 0 0 18px;
            color: #71808c;
            font-size: 13px;
        }

        .password-breadcrumb a {
            color: #1769c2;
            text-decoration: none;
        }

        .password-breadcrumb a:hover {
            text-decoration: underline;
        }

        .password-header {
            margin-bottom: 18px;
        }

        .password-eyebrow {
            margin: 0 0 7px;
            color: #1769c2;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .password-header h1 {
            margin: 0 0 7px;
            color: #183247;
            font-size: 30px;
            line-height: 1.2;
        }

        .password-header p:last-child {
            margin: 0;
            color: #657482;
            font-size: 14px;
        }

        .password-card {
            display: grid;
            grid-template-columns: 250px 1fr;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #d9e3eb;
            border-radius: 13px;
            box-shadow: 0 5px 16px rgba(48, 77, 102, 0.08);
        }

        .password-side {
            padding: 26px 22px;
            background: #e3f0f9;
            border-right: 1px solid #d2e3ef;
        }

        .password-side-mark {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            margin-bottom: 18px;
            color: #1769c2;
            background: #ffffff;
            border: 1px solid #cfe2ef;
            border-radius: 10px;
            font-size: 22px;
        }

        .password-side h2 {
            margin: 0 0 9px;
            color: #183247;
            font-size: 18px;
            line-height: 1.3;
        }

        .password-side p {
            margin: 0;
            color: #607789;
            font-size: 13px;
            line-height: 1.55;
        }

        .password-side-note {
            margin-top: 25px !important;
            padding-top: 14px;
            border-top: 1px solid #c7dce9;
            font-size: 12px !important;
        }

        .password-form-area {
            padding: 26px 28px;
        }

        .password-success {
            margin: 0 0 16px;
            padding: 11px 13px;
            color: #17663a;
            background: #e9f7ef;
            border: 1px solid #b8e3c8;
            border-radius: 7px;
            font-size: 13px;
        }

        .password-form {
            display: grid;
            gap: 16px;
        }

        .password-field {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .password-field label {
            color: #344b5d;
            font-size: 13px;
            font-weight: 700;
        }

        .password-field input {
            width: 100%;
            padding: 11px 12px;
            color: #243746;
            background: #ffffff;
            border: 1px solid #cbd8e1;
            border-radius: 7px;
            font: inherit;
            font-size: 14px;
        }

        .password-field input:focus {
            border-color: #1769c2;
            outline: 3px solid rgba(23, 105, 194, 0.12);
        }

        .password-error {
            margin: 0;
            color: #b42318;
            font-size: 12px;
        }

        .password-help {
            margin: -3px 0 0;
            color: #71808c;
            font-size: 12px;
            line-height: 1.5;
        }

        .password-actions {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-top: 4px;
            padding-top: 17px;
            border-top: 1px solid #e7eef3;
        }

        .password-submit,
        .password-cancel {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 7px;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
        }

        .password-submit {
            color: #ffffff;
            background: #1769c2;
            border: 1px solid #1769c2;
        }

        .password-submit:hover {
            color: #ffffff;
            background: #1057a4;
        }

        .password-cancel {
            color: #405466;
            background: #edf3f7;
            border: 1px solid #d6e1e8;
        }

        .password-cancel:hover {
            color: #243746;
            background: #e0eaf0;
        }

        @media (max-width: 680px) {
            .password-page {
                width: 92%;
            }

            .password-card {
                grid-template-columns: 1fr;
            }

            .password-side {
                border-right: 0;
                border-bottom: 1px solid #d2e3ef;
            }

            .password-form-area {
                padding: 22px 18px;
            }

            .password-header h1 {
                font-size: 27px;
            }
        }
    </style>

    <section class="password-page">
        <p class="password-breadcrumb">
            <a href="{{ route('profile.edit') }}">My Profile</a>
            <span> / Change Password</span>
        </p>

        <header class="password-header">
            <p class="password-eyebrow">Account security</p>
            <h1>Change Password</h1>
            <p>Update your sign-in password for the Sukli POS account.</p>
        </header>

        <div class="password-card">
            <aside class="password-side">
                <div class="password-side-mark">↻</div>

                <h2>Keep your account secure</h2>

                <p>
                    Use your current password to confirm your identity before saving a new one.
                </p>

                <p class="password-side-note">
                    Choose a password that is difficult for others to guess and avoid reusing an old password.
                </p>
            </aside>

            <div class="password-form-area">
                @if (session('password-updated'))
                    <p class="password-success">
                        {{ session('password-updated') }}
                    </p>
                @endif

                <form
                    class="password-form"
                    method="POST"
                    action="{{ route('profile.password.update') }}"
                >
                    @csrf
                    @method('PATCH')

                    <div class="password-field">
                        <label for="current_password">Current Password</label>

                        <input
                            id="current_password"
                            name="current_password"
                            type="password"
                            autocomplete="current-password"
                            required
                        >

                        @error('current_password')
                            <p class="password-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="password-field">
                        <label for="password">New Password</label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            required
                        >

                        <p class="password-help">
                            Use a password that meets the system’s security requirements.
                        </p>

                        @error('password')
                            <p class="password-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="password-field">
                        <label for="password_confirmation">
                            Confirm New Password
                        </label>

                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <div class="password-actions">
                        <button class="password-submit" type="submit">
                            Save New Password
                        </button>

                        <a
                            class="password-cancel"
                            href="{{ route('profile.edit') }}"
                        >
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection