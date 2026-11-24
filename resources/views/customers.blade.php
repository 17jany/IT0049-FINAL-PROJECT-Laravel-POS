@extends('layouts.app')

@section('title', $showArchived ? 'Archived Customers' : 'Customers')

@section('content')
<div class="customers-page">

    <div class="page-heading">
        <div>
            <p class="eyebrow">CUSTOMER DIRECTORY</p>

            <h1>
                {{ $showArchived ? 'Archived Customers' : 'Customers' }}
            </h1>

            <p class="subtitle">
                Manage customer names, contact details, and store records.
            </p>
        </div>

        @if (!$showArchived)
            <a href="{{ route('customers.create') }}" class="primary-button">
                + Add Customer
            </a>
        @endif
    </div>

    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->has('delete'))
        <div class="error-message">
            {{ $errors->first('delete') }}
        </div>
    @endif

    <div class="customer-tabs">
        <a
            href="{{ route('customers.index') }}"
            class="{{ !$showArchived ? 'active' : '' }}"
        >
            Active Customers
        </a>

        <a
            href="{{ route('customers.index', ['archived' => 1]) }}"
            class="{{ $showArchived ? 'active' : '' }}"
        >
            Archived Customers
        </a>
    </div>

    <div class="customer-card">

        <form method="GET" action="{{ route('customers.index') }}" class="search-form">
            @if ($showArchived)
                <input type="hidden" name="archived" value="1">
            @endif

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search by name, phone, or email..."
            >

            <button type="submit" class="search-button">
                Search
            </button>
        </form>

        <div class="table-wrapper">
            <table class="customers-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($customers as $customer)
                        @php
                            $initial = strtoupper(
                                substr($customer->full_name, 0, 1)
                            );

                            $isSuki = ($customer->sales_count ?? 0) > 0;
                        @endphp

                        <tr>
                            <td>
                                <div class="customer-profile">
                                    <div class="initial-avatar">
                                        {{ $initial }}
                                    </div>

                                    <div>
                                        <strong>
                                            {{ $customer->full_name }}
                                        </strong>

                                        <small>
                                            {{ $customer->email }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                {{ $customer->phone ?: '—' }}
                            </td>

                            <td>
                                @if ($showArchived)
                                    <span class="status archived-status">
                                        Archived
                                    </span>
                                @elseif ($isSuki)
                                    <span class="status suki-status">
                                        Suki
                                    </span>
                                @else
                                    <span class="status first-time-status">
                                        First-time
                                    </span>
                                @endif
                            </td>

                            <td class="actions">
                                @if ($showArchived)
                                    <form
                                        method="POST"
                                        action="{{ route('customers.restore', $customer) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="restore-button"
                                        >
                                            Restore
                                        </button>
                                    </form>

                                    <form
                                        method="POST"
                                        action="{{ route('customers.forceDestroy', $customer) }}"
                                        onsubmit="return confirm('Permanently delete this customer? This cannot be undone.')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="delete-button"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                @else
                                    <a
                                        href="{{ route('customers.edit', $customer) }}"
                                        class="edit-button"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('customers.destroy', $customer) }}"
                                        onsubmit="return confirm('Archive this customer?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="archive-button"
                                        >
                                            Archive
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                No customers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-area">
            {{ $customers->links() }}
        </div>
    </div>
</div>

<style>
    .customers-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 28px 20px 60px;
    }

    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 24px;
        margin-bottom: 20px;
    }

    .eyebrow {
        margin: 0 0 6px;
        color: #1667b9;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
    }

    .page-heading h1 {
        margin: 0;
        color: #12304b;
        font-size: 32px;
    }

    .subtitle {
        margin: 7px 0 0;
        color: #60758a;
    }

    .primary-button,
    .search-button {
        border: 0;
        border-radius: 8px;
        background: #176bc1;
        color: white;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .primary-button {
        padding: 12px 17px;
        white-space: nowrap;
    }

    .success-message,
    .error-message {
        margin-bottom: 16px;
        padding: 12px 15px;
        border-radius: 8px;
    }

    .success-message {
        background: #e8f7ef;
        border: 1px solid #b9e6cc;
        color: #216b40;
    }

    .error-message {
        background: #fff0f0;
        border: 1px solid #e6b6b6;
        color: #9d3030;
    }

    .customer-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 14px;
    }

    .customer-tabs a {
        padding: 9px 13px;
        border: 1px solid #cbddea;
        border-radius: 7px;
        background: #f5f9fc;
        color: #44647d;
        font-size: 13px;
        text-decoration: none;
    }

    .customer-tabs a.active {
        border-color: #176bc1;
        background: #176bc1;
        color: white;
    }

    .customer-card {
        overflow: hidden;
        padding: 18px;
        border: 1px solid #d7e2eb;
        border-radius: 12px;
        background: white;
        box-shadow: 0 8px 22px rgba(37, 70, 95, 0.08);
    }

    .search-form {
        display: flex;
        gap: 9px;
        margin-bottom: 22px;
    }

    .search-form input {
        flex: 1;
        min-width: 0;
        padding: 11px 13px;
        border: 1px solid #bfd2e1;
        border-radius: 7px;
        color: #19344d;
        font-size: 14px;
    }

    .search-button {
        padding: 0 17px;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .customers-table {
        width: 100%;
        border-collapse: collapse;
        color: #18344d;
        font-size: 14px;
    }

    .customers-table th {
        padding: 12px 13px;
        background: #edf5fa;
        color: #45647a;
        font-size: 11px;
        letter-spacing: 1px;
        text-align: left;
        text-transform: uppercase;
    }

    .customers-table td {
        padding: 13px;
        border-bottom: 1px solid #e1eaf0;
        vertical-align: middle;
    }

    .customer-profile {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .customer-profile strong {
        display: block;
        color: #17344f;
    }

    .customer-profile small {
        display: block;
        margin-top: 3px;
        color: #6d8294;
        font-size: 12px;
    }

    .initial-avatar {
        display: grid;
        width: 36px;
        height: 36px;
        place-items: center;
        border: 1px solid #c9dfef;
        border-radius: 50%;
        background: #e7f3fb;
        color: #176bc1;
        font-weight: 700;
    }

    .status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .suki-status {
        background: #e5f7ee;
        color: #26724d;
    }

    .first-time-status {
        background: #e8f3fb;
        color: #39749f;
    }

    .archived-status {
        background: #fff1d8;
        color: #8a6500;
    }

    .actions {
        display: flex;
        gap: 7px;
        white-space: nowrap;
    }

    .actions form {
        display: inline;
        margin: 0;
    }

    .edit-button,
    .restore-button,
    .archive-button,
    .delete-button {
        padding: 7px 10px;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        text-decoration: none;
    }

    .edit-button,
    .restore-button {
        border: 1px solid #b8d9f3;
        background: #edf7ff;
        color: #1264af;
    }

    .archive-button {
        border: 1px solid #edcc75;
        background: #fff8e4;
        color: #8b6500;
    }

    .delete-button {
        border: 1px solid #e3b1b1;
        background: #fff1f1;
        color: #a02c2c;
    }

    .empty-state {
        padding: 35px !important;
        color: #71879a;
        text-align: center;
    }

    .pagination-area {
        margin-top: 18px;
    }

    @media (max-width: 700px) {
        .page-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .search-form {
            flex-direction: column;
        }

        .search-button {
            min-height: 40px;
        }
    }
</style>
@endsection