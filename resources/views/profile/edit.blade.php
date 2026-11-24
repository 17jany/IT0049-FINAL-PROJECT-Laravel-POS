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
            max-width: 980px;
            margin: 24px auto;
        }

        .profile-heading {
            margin-bottom: 20px;
        }

        .profile-eyebrow {
            margin: 0 0 7px;
            color: #1769c2;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .profile-heading h1 {
            margin: 0 0 7px;
            color: #183247;
            font-size: 32px;
            line-height: 1.15;
        }

        .profile-heading p:last-child {
            margin: 0;
            color: #657482;
            font-size: 14px;
        }

        .profile-card {
            display: grid;
            grid-template-columns: 280px 1fr;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #d9e3eb;
            border-radius: 14px;
            box-shadow: 0 5px 16px rgba(48, 77, 102, 0.09);
        }

        .profile-identity {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 26px 24px;
            background: #e3f0f9;
            border-right: 1px solid #d2e3ef;
        }

        .profile-identity-top {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .profile-avatar {
            width: 70px;
            height: 70px;
            flex: 0 0 70px;
            object-fit: cover;
            border: 4px solid #ffffff;
            border-radius: 50%;
            box-shadow: 0 2px 7px rgba(23, 105, 194, 0.18);
        }

        .profile-identity-label {
            margin: 0 0 5px;
            color: #1769c2;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .profile-identity h2 {
            margin: 0 0 4px;
            color: #183247;
            font-size: 18px;
            line-height: 1.25;
        }

        .profile-username {
            margin: 0;
            color: #657482;
            font-size: 13px;
        }

        .profile-note {
            margin: 28px 0 0;
            padding-top: 15px;
            color: #607789;
            border-top: 1px solid #c7dce9;
            font-size: 12px;
            line-height: 1.5;
        }

        .profile-details {
            padding: 26px 28px;
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
            color: #344b5d;
            font-size: 13px;
            font-weight: 700;
        }

        .profile-field input {
            width: 100%;
            padding: 11px 12px;
            color: #243746;
            background: #ffffff;
            border: 1px solid #cbd8e1;
            border-radius: 7px;
            font: inherit;
            font-size: 14px;
        }

        .profile-field input:focus {
            border-color: #1769c2;
            outline: 3px solid rgba(23, 105, 194, 0.12);
        }

        .profile-field-error {
            margin: 0;
            color: #b42318;
            font-size: 12px;
        }

        .profile-meta-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 3px;
        }

        .profile-meta {
            padding: 12px 13px;
            background: #f5f9fc;
            border: 1px solid #e0e9ef;
            border-radius: 7px;
        }

        .profile-meta span {
            display: block;
            margin-bottom: 5px;
            color: #71808c;
            font-size: 11px;
        }

        .profile-meta strong {
            color: #243746;
            font-size: 13px;
        }

        .profile-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 4px;
        }

        .profile-actions-left {
            display: flex;
            gap: 9px;
        }

        .profile-save-button,
        .profile-cancel-link,
        .profile-security-link {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 7px;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
        }

        .profile-save-button {
            color: #ffffff;
            background: #1769c2;
            border: 1px solid #1769c2;
        }

        .profile-save-button:hover {
            color: #ffffff;
            background: #1057a4;
        }

        .profile-cancel-link {
            color: #405466;
            background: #edf3f7;
            border: 1px solid #d6e1e8;
        }

        .profile-cancel-link:hover {
            color: #243746;
            background: #e0eaf0;
        }

        .profile-security-link {
            color: #735500;
            background: #fff4c7;
            border: 1px solid #f0d979;
        }

        .profile-security-link:hover {
            color: #624800;
            background: #ffeaa2;
        }

        .profile-security {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-top: 15px;
            padding: 15px 18px;
            background: #fffdf4;
            border: 1px solid #f0e2a9;
            border-radius: 10px;
        }

        .profile-security p {
            margin: 0;
            color: #6d6040;
            font-size: 13px;
        }

        .profile-success {
            margin: 0 0 16px;
            padding: 11px 13px;
            color: #17663a;
            background: #e9f7ef;
            border: 1px solid #b8e3c8;
            border-radius: 7px;
            font-size: 13px;
        }

        @media (max-width: 700px) {
            .profile-page {
                width: 92%;
            }

            .profile-card {
                grid-template-columns: 1fr;
            }

            .profile-identity {
                border-right: 0;
                border-bottom: 1px solid #d2e3ef;
            }

            .profile-details {
                padding: 22px 18px;
            }
        }

        @media (max-width: 500px) {
            .profile-heading h1 {
                font-size: 27px;
            }

            .profile-meta-list {
                grid-template-columns: 1fr;
            }

            .profile-actions,
            .profile-security {
                align-items: flex-start;
                flex-direction: column;
            }

            .profile-actions-left {
                width: 100%;
            }
        }
    </style>

    <section class="profile-page">
        <header class="profile-heading">
            <p class="profile-eyebrow">User profile</p>
            <h1>My Account</h1>
            <p>Manage the personal details connected to your POS account.</p>
        </header>

        @if (session('status') === 'profile-updated')
            <p class="profile-success">
                Your profile was updated successfully.
            </p>
        @endif

        <div class="profile-card">
            <aside class="profile-identity">
                <div class="profile-identity-top">
                    <img
                        class="profile-avatar"
                        src="{{ $avatarUrl }}"
                        alt="{{ $profile->full_name }}'s profile picture"
                    >

                    <div>
                        <p class="profile-identity-label">POS staff account</p>
                        <h2>{{ $profile->full_name }}</h2>
                        <p class="profile-username">
                            {{ '@' . $profile->username }}
                        </p>
                    </div>
                </div>

                <p class="profile-note">
                    Keep your account information updated so staff records remain accurate.
                </p>
            </aside>

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

                    <div class="profile-meta-list">
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
                    </div>

                    <div class="profile-actions">
                        <div class="profile-actions-left">
                            <button class="profile-save-button" type="submit">
                                Save Profile
                            </button>

                            <a class="profile-cancel-link" href="{{ route('dashboard') }}">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="profile-security">
            <p>Need to update your password?</p>

            <a
                class="profile-security-link"
                href="{{ route('profile.password.edit') }}"
            >
                Change Password
            </a>
        </div>
    </section>
@endsection