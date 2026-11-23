@extends('layouts.app')

@section('title', 'Sales History')

@section('content')
<section class="sales-history-page">

    <div class="sales-heading">
        <p class="eyebrow">SALES / SALES HISTORY</p>
        <h1>Sales History</h1>
        <p class="subtitle">
            View and manage recorded sales.
        </p>
    </div>

    @if (session('success'))
        <p class="success-message">
            {{ session('success') }}
        </p>
    @endif

    @if ($errors->any())
        <div class="sales-error" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="sales-panel">

        <div class="sales-toolbar">
            <form
                action="{{ route('sales.index') }}"
                method="GET"
                class="sales-filter-form"
                id="salesFilterForm">

                <div class="filter-field">
                    <label for="date_from">Date From</label>
                    <input
                        type="date"
                        id="date_from"
                        name="date_from"
                        value="{{ request('date_from') }}">
                </div>

                <div class="filter-field">
                    <label for="date_to">Date To</label>
                    <input
                        type="date"
                        id="date_to"
                        name="date_to"
                        value="{{ request('date_to') }}">
                </div>

                <div class="filter-field">
                    <label for="cashier_id">Cashier</label>
                    <select id="cashier_id" name="cashier_id">
                        <option value="">All Cashiers</option>

                        @foreach ($cashiers as $cashier)
                            <option
                                value="{{ $cashier->id }}"
                                @selected(request('cashier_id') == $cashier->id)>
                                {{ $cashier->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-field">
                    <label for="customer_id">Customer</label>
                    <select id="customer_id" name="customer_id">
                        <option value="">All Customers</option>

                        @foreach ($customers as $customer)
                            <option
                                value="{{ $customer->id }}"
                                @selected(request('customer_id') == $customer->id)>
                                {{ $customer->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="filter-button">
                        Filter
                    </button>

                    <a href="{{ route('sales.index') }}"
                       class="clear-button">
                        Clear
                    </a>
                </div>
            </form>

            <div class="sales-top-actions">
                <a href="{{ route('sales.create') }}"
                   class="record-button">
                    + Record Sale
                </a>

                <a href="{{ route('sales.export', request()->query()) }}"
                   class="export-button">
                    Export CSV
                </a>
            </div>
        </div>

        <div class="sales-table-wrapper">
            <table class="sales-table">
                <thead>
                    <tr>
                        <th>Receipt ID</th>
                        <th>Date / Time</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Cashier</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($sales as $sale)
                        <tr>
                            <td>
                                <strong>
                                    R-{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
                                </strong>
                            </td>

                            <td>
                                {{ $sale->created_at->format('M d, Y h:i A') }}
                            </td>

                            <td>
                                @foreach ($sale->items as $item)
                                    <div class="sale-product">
                                        {{ $item->product?->name ?? 'Unavailable Product' }}
                                        × {{ $item->quantity }}
                                    </div>
                                @endforeach
                            </td>

                            <td>
                                {{ $sale->customer?->full_name ?? 'Walk-in Customer' }}
                            </td>

                            <td>
                                {{ $sale->user?->full_name ?? 'Unknown Staff' }}
                            </td>

                            <td class="sale-amount">
                                ₱{{ number_format($sale->total_amount, 2) }}
                            </td>

                            <td>
                                <a href="{{ route('sales.show', $sale) }}"
                                   class="details-link">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-message">
                                No sales transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="sales-footer">
            <p>
                Showing {{ $sales->firstItem() ?? 0 }}
                –
                {{ $sales->lastItem() ?? 0 }}
                of {{ $sales->total() }} sales
            </p>

            <div class="sales-pagination">
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</section>

<style>
    .sales-history-page {
        width: min(100%, 1250px);
        margin: 20px auto;
    }

    .sales-heading {
        margin-bottom: 22px;
    }

    .sales-heading h1 {
        font-size: 32px;
        margin: 6px 0;
    }

    .sales-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 20px;
    }

    .sales-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }

    .sales-filter-form {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 10px;
        flex: 1;
    }

    .filter-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-field label {
        font-size: 12px;
        font-weight: 600;
        color: #475569;
    }

    .filter-field input,
    .filter-field select {
        padding: 10px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font: inherit;
        font-size: 12px;
        background: #ffffff;
    }

    .filter-field select {
        max-width: 170px;
    }

    .filter-actions,
    .sales-top-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-button,
    .record-button,
    .export-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #1769c2;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        padding: 10px 13px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .filter-button:hover,
    .record-button:hover,
    .export-button:hover {
        background: #1255a0;
    }

    .clear-button {
        color: #1769c2;
        font-size: 12px;
        text-decoration: none;
        font-weight: 600;
    }

    .sales-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .sales-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .sales-table th,
    .sales-table td {
        padding: 13px 10px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
        vertical-align: middle;
    }

    .sales-table th {
        background: #edf2f7;
        color: #475569;
        font-weight: 600;
        white-space: nowrap;
    }

    .sales-table td {
        color: #334155;
    }

    .sale-product {
        margin-bottom: 4px;
        min-width: 130px;
    }

    .sale-amount {
        font-weight: 700;
        white-space: nowrap;
    }

    .details-link {
        color: #1769c2;
        font-weight: 600;
        text-decoration: underline;
    }

    .sales-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .sales-footer p {
        font-size: 12px;
        color: #64748b;
    }

    .sales-error {
        background: #fee2e2;
        color: #991b1b;
        border-radius: 7px;
        padding: 12px;
        margin-bottom: 18px;
    }

    @media (max-width: 700px) {
        .sales-toolbar {
            align-items: stretch;
            flex-direction: column;
        }

        .sales-filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-field select {
            max-width: none;
        }
    }
</style>
@endsection
