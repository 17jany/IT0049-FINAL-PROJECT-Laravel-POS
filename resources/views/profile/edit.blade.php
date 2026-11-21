@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    @php
        $profile = Auth::user();

        $avatarUrl = $profile->avatar
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($profile->avatar)
            : asset('images/avatar-placeholder.svg');
    @endphp

    <style>
        .profile-page {
            width: 100%;
            margin: 24px auto;
        }

        .profile-heading {
            margin-bottom: 18px;
        }

        .profile-heading .eyebrow {
            margin-bottom: 8px;
        }

        .profile-heading h1 {
            margin: 0 0 8px;
            color: #252525;
            font-size: 30px;
        }

        .profile-heading p:last-child {
            margin: 0;
            color: #626262;
            font-size: 15px;
        }

        .profile-card {
            display: grid;
            grid-template-columns: minmax(240px, 0.8fr) minmax(0, 1.4fr);
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #e3e3e3;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.09);
        }

        .profile-identity {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 28px 24px;
            color: #ffffff;
            background: #171717;
        }

        .profile-avatar {
            width: 72px;
            height: 72px;
            flex: 0 0 72px;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.8);
            border-radius: 50%;
        }

        .profile-identity p {
            margin: 0 0 6px;
        }

        .profile-identity .profile-label {
            color: #c7c7c7;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .profile-identity h2 {
            margin: 0 0 5px;
            font-size: 19px;
            line-height: 1.3;
        }

        .profile-identity .profile-username {
            color: #d0d0d0;
            font-size: 13px;
        }

        .profile-details {
            padding: 22px 24px;
        }

        .profile-form {
            display: grid;
            gap: 15px;
        }

        .profile-field {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .profile-field label {
            color: #333333;
            font-size: 14px;
            font-weight: bold;
        }

        .profile-field input {
            width: 100%;
            padding: 10px 12px;
            color: #252525;
            background: #ffffff;
            border: 1px solid #cccccc;
            border-radius: 7px;
            font: inherit;
            font-size: 14px;
        }

        .profile-field input:focus {
            border-color: #555555;
            outline: 2px solid #dddddd;
        }

        .profile-field-error {
            margin: 0;
            color: #b42318;
            font-size: 13px;
        }

        .profile-meta {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 12px 0;
            border-top: 1px solid #e9e9e9;
            color: #626262;
            font-size: 13px;
        }

        .profile-meta strong {
            color: #252525;
            text-align: right;
        }

        .profile-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 4px;
        }

        .profile-save-button,
        .profile-cancel-link {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 7px;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
        }

        .profile-save-button {
            color: #ffffff;
            background: #171717;
            border: 1px solid #171717;
        }

        .profile-save-button:hover {
            background: #404040;
        }

        .profile-cancel-link {
            color: #252525;
            background: #eeeeee;
            border: 1px solid #dddddd;
        }

        .profile-cancel-link:hover {
            background: #dddddd;
        }

        .profile-success {
            margin: 0 0 16px;
            padding: 11px 13px;
            color: #17663a;
            background: #e9f7ef;
            border: 1px solid #b8e3c8;
            border-radius: 7px;
            font-size: 14px;
        }

        .password-card {
            display: grid;
            gap: 14px;
            margin-top: 18px;
            padding: 22px 24px;
            background: #ffffff;
            border: 1px solid #e3e3e3;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        }

        .password-card h2 {
            margin: -8px 0 0;
            color: #252525;
            font-size: 21px;
        }

        .password-description {
            margin: -6px 0 4px;
            color: #626262;
            font-size: 14px;
            line-height: 1.5;
        }

        .password-card form {
            display: grid;
            gap: 15px;
        }

        @media (max-width: 650px) {
            .profile-card {
                grid-template-columns: 1fr;
            }

            .profile-identity {
                padding: 22px 18px;
            }

            .profile-details {
                padding: 18px;
            }

            .profile-heading h1 {
                font-size: 26px;
            }

            .password-card {
                padding: 18px;
            }
        }
    </style>

    <section class="profile-page">
        <header class="profile-heading">
            <p class="eyebrow">User Profile</p>
            <h1>My Account</h1>
            <p>Edit the details for your own POS account.</p>
        </header>

        @if (session('status') === 'profile-updated')
            <p class="profile-success">Your profile was updated successfully.</p>
        @endif

        <div class="profile-card">
            <div class="profile-identity">
                <img
                    class="profile-avatar"
                    src="{{ $avatarUrl }}"
                    alt="{{ $profile->full_name }}'s profile picture"
                >

                <div>
                    <p class="profile-label">POS Staff Account</p>
                    <h2>{{ $profile->full_name }}</h2>
                    <p class="profile-username">
                        {{ '@' . $profile->username }}
                    </p>
                </div>
            </div>

            <div class="profile-details">
                <form
                    class="profile-form"
                    method="POST"
                    action="{{ route('profile.update') }}"
                >
                    @csrf
                    @method('PATCH')

                    <div class="profile-field">
                        <label for="full_name">Full Name</label>
                        <input
                            id="full_name"
                            name="full_name"
                            type="text"
                            value="{{ old('full_name', $profile->full_name) }}"
                            required
                        >
                        @error('full_name')
                            <p class="profile-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="profile-field">
                        <label for="username">Username</label>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            value="{{ old('username', $profile->username) }}"
                            required
                        >
                        @error('username')
                            <p class="profile-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="profile-field">
                        <label for="email">Email Address</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $profile->email) }}"
                        >
                        @error('email')
                            <p class="profile-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="profile-meta">
                        <span>Account created</span>
                        <strong>
                            {{ $profile->created_at?->format('F j, Y') ?? 'Not available' }}
                        </strong>
                    </div>

                    <div class="profile-meta">
                        <span>Account type</span>
                        <strong>POS Staff</strong>
                    </div>

                    <div class="profile-actions">
                        <button class="profile-save-button" type="submit">
                            Save Profile
                        </button>

                        <a class="profile-cancel-link" href="{{ route('home') }}">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <section class="password-card">
            <p class="eyebrow">Account Security</p>
            <h2>Change Password</h2>
            <p class="password-description">
                Enter your current password, then choose and confirm a new password.
            </p>

            @if (session('password-updated'))
                <p class="profile-success">
                    {{ session('password-updated') }}
                </p>
            @endif

            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                @method('PATCH')

                <div class="profile-field">
                    <label for="current_password">Current Password</label>
                    <input
                        id="current_password"
                        name="current_password"
                        type="password"
                        autocomplete="current-password"
                        required
                    >
                    @error('current_password')
                        <p class="profile-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-field">
                    <label for="password">New Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="new-password"
                        required
                    >
                    @error('password')
                        <p class="profile-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-field">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <button class="profile-save-button" type="submit">
                    Update Password
                </button>
            </form>
        </section>
    </section>
@endsection