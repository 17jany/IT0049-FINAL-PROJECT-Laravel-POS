@extends('layouts.app')

@section('title', 'Transaction Details')

@section('content')
<section class="table-section sale-details">

    <div class="details-header">
        <div>
            <p class="eyebrow">SALES MANAGEMENT</p>
            <h1>Transaction Details</h1>
            <p class="subtitle">
                Details of the recorded sales transaction.
            </p>
        </div>

        <div class="receipt-actions">
            <a href="{{ route('sales.receipt', $sale) }}"
               class="download-receipt-button">
                Download Receipt (PDF)
            </a>

            <button type="button"
                    class="print-receipt-button"
                    onclick="window.print()">
                Print Receipt
            </button>

            <a href="{{ route('sales.index') }}"
               class="back-button">
                Back to Sales History
            </a>
        </div>
    </div>

    <div class="transaction-info">
        <p>
            <strong>Transaction ID:</strong>
            R-{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
        </p>

        <p>
            <strong>Date & Time:</strong>
            {{ $sale->created_at->format('M d, Y h:i A') }}
        </p>

        <p>
            <strong>Customer:</strong>
            {{ $sale->customer?->full_name ?? 'Walk-in Customer' }}
        </p>

        <p>
            <strong>Recorded By:</strong>
            {{ $sale->user?->full_name ?? 'Unknown Staff' }}
        </p>

        <p>
            <strong>Payment Method:</strong>
            {{ ucfirst($sale->payment_method ?? 'cash') }}
        </p>
    </div>

    <h2>Purchased Products</h2>

    <div class="details-table-wrapper">
        <table class="details-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($sale->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ $item->product?->name ?? 'Product Unavailable' }}
                        </td>

                        <td>{{ $item->quantity }}</td>

                        <td>
                            ₱{{ number_format($item->unit_price, 2) }}
                        </td>

                        <td>
                            ₱{{ number_format($item->subtotal, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-message">
                            No products found for this transaction.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="payment-summary">
        <div class="payment-row">
            <span>Subtotal</span>
            <strong>
                ₱{{ number_format($sale->total_amount, 2) }}
            </strong>
        </div>

        <div class="payment-row">
            <span>Cash Received</span>
            <strong>
                @if ($sale->cash_received !== null)
                    ₱{{ number_format($sale->cash_received, 2) }}
                @else
                    Not recorded
                @endif
            </strong>
        </div>

        <div class="payment-row">
            <span>Change (Sukli)</span>
            <strong>
                @if ($sale->cash_received !== null)
                    ₱{{ number_format($sale->change_amount, 2) }}
                @else
                    Not recorded
                @endif
            </strong>
        </div>
    </div>

    <div class="transaction-total">
        <span>Total Amount</span>
        <strong>
            ₱{{ number_format($sale->total_amount, 2) }}
        </strong>
    </div>

</section>

<style>
    .sale-details {
        width: min(100%, 1050px);
        margin: 18px auto;
        padding: 24px;
        box-sizing: border-box;
    }

    .details-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .details-header h1 {
        margin-bottom: 8px;
    }

    .receipt-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
    }

    .download-receipt-button,
    .print-receipt-button,
    .back-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
        box-sizing: border-box;
    }

    .download-receipt-button,
    .print-receipt-button {
        background: #1769c2;
        color: #ffffff;
        border: 1px solid #1769c2;
    }

    .download-receipt-button:hover,
    .print-receipt-button:hover {
        background: #1255a0;
    }

    .back-button {
        background: #17191c;
        color: #ffffff;
        border: 1px solid #17191c;
    }

    .back-button:hover {
        background: #30343a;
    }

    .transaction-info {
        padding: 18px;
        background: #f5f5f5;
        border-radius: 8px;
        margin-bottom: 24px;
    }

    .transaction-info p {
        margin: 8px 0;
    }

    .sale-details h2 {
        font-size: 23px;
        margin-bottom: 16px;
    }

    .details-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .details-table {
        width: 100%;
        border-collapse: collapse;
    }

    .details-table th,
    .details-table td {
        padding: 13px 12px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .details-table th {
        background: #17191c;
        color: #ffffff;
        font-size: 12px;
        white-space: nowrap;
    }

    .details-table td {
        font-size: 13px;
    }

    .payment-summary {
        margin-top: 24px;
        padding: 18px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }

    .payment-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 9px 0;
        font-size: 14px;
    }

    .payment-row:not(:last-child) {
        border-bottom: 1px solid #e2e8f0;
    }

    .transaction-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-top: 18px;
        padding: 18px;
        background: #f1f5f9;
        border-radius: 8px;
        font-size: 20px;
    }

    .transaction-total strong {
        color: #1769c2;
        font-size: 23px;
    }

    @media (max-width: 800px) {
        .details-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .receipt-actions {
            justify-content: flex-start;
        }
    }

    @media (max-width: 600px) {
        .sale-details {
            padding: 16px;
        }

        .receipt-actions {
            flex-direction: column;
            align-items: stretch;
            width: 100%;
        }

        .transaction-total {
            font-size: 16px;
        }
    }

    @media print {
        @page {
            margin: 12mm;
        }

        body {
            background: #ffffff !important;
        }

        nav,
        header,
        .receipt-actions {
            display: none !important;
        }

        .sale-details {
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none;
            border: none;
        }

        .transaction-info,
        .payment-summary,
        .transaction-total {
            break-inside: avoid;
        }

        .details-table th {
            background: #eeeeee !important;
            color: #000000 !important;
        }
    }
</style>
@endsection
