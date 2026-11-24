@extends('layouts.app')

@section('title', 'Staff Accounts')

@section('content')
    <style>
        .staff-page {
            width: 100%;
            margin: 24px auto;
        }

        .staff-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
        }

        .staff-eyebrow {
            margin: 0 0 7px;
            color: #1769c2;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .staff-header h1 {
            margin: 0 0 7px;
            color: #183247;
            font-size: 30px;
            line-height: 1.2;
        }

        .staff-subtitle {
            margin: 0;
            color: #657482;
            font-size: 14px;
        }

        .staff-add-button {
            display: inline-block;
            padding: 10px 14px;
            color: #ffffff;
            background: #1769c2;
            border: 1px solid #1769c2;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
        }

        .staff-add-button:hover {
            color: #ffffff;
            background: #1057a4;
        }

        .staff-summary {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 14px;
            padding: 10px 13px;
            color: #405466;
            background: #e3f0f9;
            border: 1px solid #cfe2ef;
            border-radius: 8px;
            font-size: 13px;
        }

        .staff-summary-number {
            color: #1769c2;
            font-size: 20px;
            font-weight: 700;
        }

        .staff-card {
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #d9e3eb;
            border-radius: 12px;
            box-shadow: 0 5px 16px rgba(48, 77, 102, 0.08);
        }

        .staff-table-wrap {
            overflow-x: auto;
        }

        .staff-table {
            width: 100%;
            min-width: 650px;
            margin: 0;
            border-collapse: collapse;
            font-size: 13px;
        }

        .staff-table th {
            padding: 12px 16px;
            color: #466072;
            background: #edf5fa;
            border-bottom: 1px solid #d6e4ed;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-align: left;
            text-transform: uppercase;
        }

        .staff-table td {
            padding: 13px 16px;
            color: #344b5d;
            border-bottom: 1px solid #e7eef3;
            vertical-align: middle;
        }

        .staff-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .staff-table tbody tr:hover {
            background: #f8fbfd;
        }

        .staff-number {
            width: 58px;
            color: #8393a0 !important;
            font-size: 12px;
        }

        .staff-person {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 210px;
        }

        .staff-avatar {
            width: 43px;
            height: 43px;
            flex: 0 0 43px;
            object-fit: cover;
            border: 2px solid #d7e7f1;
            border-radius: 50%;
            background: #eef5f9;
        }

        .staff-person-name {
            display: block;
            color: #183247;
            font-weight: 700;
        }

        .staff-person-username {
            display: block;
            margin-top: 3px;
            color: #82919d;
            font-size: 12px;
        }

        .staff-status {
            display: inline-block;
            padding: 5px 9px;
            color: #27704a;
            background: #e7f6ed;
            border: 1px solid #c5e7d2;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .staff-edit-button {
            display: inline-block;
            padding: 8px 12px;
            color: #1769c2;
            background: #edf5fc;
            border: 1px solid #c9e0f2;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
        }

        .staff-edit-button:hover {
            color: #ffffff;
            background: #1769c2;
            border-color: #1769c2;
        }

        .staff-empty {
            padding: 30px 16px !important;
            color: #7b8994 !important;
            text-align: center;
        }

        .staff-success {
            margin: 0 0 14px;
            padding: 11px 13px;
            color: #17663a;
            background: #e9f7ef;
            border: 1px solid #b8e3c8;
            border-radius: 7px;
            font-size: 13px;
        }

        @media (max-width: 650px) {
            .staff-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .staff-add-button {
                width: 100%;
                text-align: center;
            }

            .staff-header h1 {
                font-size: 27px;
            }
        }
    </style>

    <section class="staff-page">
        <header class="staff-header">
            <div>
                <p class="staff-eyebrow">Staff directory</p>
                <h1>Staff Accounts</h1>
                <p class="staff-subtitle">
                    Manage the people who can access your POS system.
                </p>
            </div>

            <a class="staff-add-button" href="{{ route('users.create') }}">
                + Add Staff
            </a>
        </header>

        @if (session('success'))
            <p class="staff-success">
                {{ session('success') }}
            </p>
        @endif

        <div class="staff-summary">
            <span class="staff-summary-number">{{ $users->count() }}</span>
            <span>
                {{ $users->count() === 1 ? 'staff account' : 'staff accounts' }}
                registered
            </span>
        </div>

        <div class="staff-card">
            <div class="staff-table-wrap">
                <table class="staff-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Staff member</th>
                            <th>Account status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="staff-number">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <div class="staff-person">
                                        <img
                                            class="staff-avatar"
                                            src="{{ $user->avatar
                                                ? asset('storage/' . $user->avatar)
                                                : asset('images/avatar-placeholder.svg') }}"
                                            alt="{{ $user->full_name }} avatar"
                                        >

                                        <div>
                                            <span class="staff-person-name">
                                                {{ $user->full_name }}
                                            </span>

                                            <span class="staff-person-username">
                                                {{ '@' . $user->username }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="staff-status">Active</span>
                                </td>

                                <td>
                                    <a
                                        class="staff-edit-button"
                                        href="{{ route('users.edit', $user) }}"
                                    >
                                        Edit account
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="staff-empty" colspan="4">
                                    No staff accounts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection