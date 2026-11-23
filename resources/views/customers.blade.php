@extends('layouts.app')

@section('title', 'Customers')

@section('content')
<section class="customers-page">

    <div class="customers-heading">
        <h1>Customers</h1>
        <p class="subtitle">
            Manage your suki, loyalty, and credit customers.
        </p>
    </div>

    @if (session('success'))
        <p class="success-message">
            {{ session('success') }}
        </p>
    @endif

    @if (session('error'))
        <p class="customer-alert" role="alert">
            {{ session('error') }}
        </p>
    @endif

    <div class="customers-panel">

        <div class="customer-toolbar">
            <form
                action="{{ route('customers.index') }}"
                method="GET"
                class="customer-search">

                <div class="search-field">
                    <span class="search-icon">⌕</span>

                    <input
                        type="search"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Search customers..."
                        aria-label="Search customers">
                </div>

                <button type="submit" class="search-button">
                    Search
                </button>
            </form>

            <a
                href="{{ route('customers.create') }}"
                class="add-customer-button">
                + Add Customer
            </a>
        </div>

        <div class="customers-table-wrapper">
            <table class="customers-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Type</th>
                        <th>Credit Limit</th>
                        <th>Balance</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td>
                                <div class="customer-identity">
                                    <span class="customer-avatar">
                                        &#128100;
                                    </span>

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
                                @if ($customer->customer_type === 'credit')
                                    <span class="customer-badge credit-badge">
                                        Credit
                                    </span>
                                @else
                                    <span class="customer-badge suki-badge">
                                        Suki
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if ($customer->customer_type === 'credit')
                                    ₱{{ number_format($customer->credit_limit, 2) }}
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                @if ($customer->customer_type === 'credit')
                                    ₱{{ number_format($customer->balance, 2) }}
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                <div class="customer-actions">
                                    <a
                                        href="{{ route('customers.edit', $customer) }}"
                                        class="action-link">
                                        Edit
                                    </a>

                                    <span class="action-divider">|</span>

                                    <form
                                        action="{{ route('customers.destroy', $customer) }}"
                                        method="POST"
                                        onsubmit="return confirm('Archive this customer?');">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-link archive-action">
                                            Archive
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-message">
                                No customer records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="customer-pagination">
            {{ $customers->links() }}
        </div>

    </div>
</section>

<style>
    .customers-page {
        width: min(100%, 1200px);
        margin: 20px auto;
    }

    .customers-heading {
        margin-bottom: 22px;
    }

    .customers-heading h1 {
        font-size: 32px;
        margin: 0 0 6px;
    }

    .customers-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 20px;
    }

    .customer-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
    }

    .customer-search {
        display: flex;
        align-items: center;
        flex: 1;
        gap: 10px;
    }

    .search-field {
        position: relative;
        flex: 1;
        min-width: 0;
    }

    .search-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 22px;
    }

    .search-field input {
        width: 100%;
        padding: 11px 12px 11px 40px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        font: inherit;
        box-sizing: border-box;
    }

    .search-button,
    .add-customer-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #1769c2;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        padding: 11px 16px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
    }

    .search-button:hover,
    .add-customer-button:hover {
        background: #1255a0;
    }

    .customers-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .customers-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .customers-table th,
    .customers-table td {
        padding: 13px 12px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .customers-table th {
        background: #edf2f7;
        color: #475569;
        font-weight: 600;
        white-space: nowrap;
    }

    .customer-identity {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 160px;
    }

    .customer-identity strong,
    .customer-identity small {
        display: block;
    }

    .customer-identity small {
        margin-top: 4px;
        color: #64748b;
        font-size: 11px;
    }

    .customer-avatar {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        border-radius: 50%;
        font-size: 17px;
        flex-shrink: 0;
    }

    .customer-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .suki-badge {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .credit-badge {
        background: #ede9fe;
        color: #6d28d9;
    }

    .customer-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .customer-actions form {
        margin: 0;
    }

    .action-link {
        color: #1769c2;
        background: none;
        border: none;
        padding: 0;
        font: inherit;
        font-size: 12px;
        font-weight: 600;
        text-decoration: underline;
        cursor: pointer;
    }

    .action-divider {
        color: #94a3b8;
    }

    .customer-pagination {
        margin-top: 20px;
    }

    .customer-alert {
        background: #fee2e2;
        color: #991b1b;
        padding: 12px;
        border-radius: 7px;
        margin-bottom: 18px;
    }

    @media (max-width: 700px) {
        .customer-toolbar {
            align-items: stretch;
            flex-direction: column;
        }

        .customer-search {
            width: 100%;
        }

        .add-customer-button {
            align-self: flex-start;
        }

        .customers-panel {
            padding: 14px;
        }
    }
</style>
@endsection
