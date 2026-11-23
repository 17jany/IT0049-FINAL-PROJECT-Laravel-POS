<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SUKLI POS Receipt</title>

    <style>
        @page {
            size: A5 portrait;
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #17191c;
        }

        .receipt {
            width: 100%;
            margin: 0 auto;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .receipt-header h1 {
            font-size: 26px;
            letter-spacing: 2px;
            margin: 0;
        }

        .receipt-header p {
            margin: 5px 0;
            font-size: 11px;
        }

        .receipt-divider {
            border-top: 1px dashed #777;
            margin: 15px 0;
        }

        .receipt-info p {
            margin: 7px 0;
            line-height: 1.5;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .receipt-table th,
        .receipt-table td {
            padding: 9px 5px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .receipt-table th {
            background: #f1f5f9;
        }

        .text-right {
            text-align: right !important;
        }

        .receipt-summary {
            margin-top: 18px;
        }

        .summary-row {
            width: 100%;
            margin: 9px 0;
        }

        .summary-row:after {
            content: "";
            display: block;
            clear: both;
        }

        .summary-label {
            float: left;
        }

        .summary-value {
            float: right;
            font-weight: bold;
        }

        .receipt-total {
            font-size: 20px;
            font-weight: bold;
            margin-top: 18px;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 25px;
            font-weight: bold;
            line-height: 1.8;
        }
    </style>
</head>

<body>
<div class="receipt">

    <div class="receipt-header">
        <h1>SUKLI POS</h1>
        <p><strong>NEIGHBORHOOD STORE</strong></p>
        <p>SALES RECEIPT</p>
    </div>

    <div class="receipt-divider"></div>

    <div class="receipt-info">
        <p>
            <strong>Receipt ID:</strong>
            R-{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
        </p>

        <p>
            <strong>Date & Time:</strong>
            {{ $sale->created_at->format('M d, Y h:i A') }}
        </p>

        <p>
            <strong>Cashier:</strong>
            {{ $sale->user?->full_name ?? 'Unknown Staff' }}
        </p>

        <p>
            <strong>Customer:</strong>
            {{ $sale->customer?->full_name ?? 'Walk-in Customer' }}
        </p>

        <p>
            <strong>Payment Method:</strong>
            {{ ucfirst($sale->payment_method ?? 'cash') }}
        </p>
    </div>

    <div class="receipt-divider"></div>

    <table class="receipt-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($sale->items as $item)
                <tr>
                    <td>
                        {{ $item->product?->name ?? 'Unavailable Product' }}
                    </td>

                    <td>{{ $item->quantity }}</td>

                    <td class="text-right">
                        PHP {{ number_format($item->unit_price, 2) }}
                    </td>

                    <td class="text-right">
                        PHP {{ number_format($item->subtotal, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="receipt-summary">

        <div class="summary-row">
            <span class="summary-label">Subtotal</span>
            <span class="summary-value">
                PHP {{ number_format($sale->total_amount, 2) }}
            </span>
        </div>

        <div class="summary-row">
            <span class="summary-label">Cash Received</span>
            <span class="summary-value">
                @if ($sale->cash_received !== null)
                    PHP {{ number_format($sale->cash_received, 2) }}
                @else
                    Not recorded
                @endif
            </span>
        </div>

        <div class="summary-row">
            <span class="summary-label">Change (Sukli)</span>
            <span class="summary-value">
                @if ($sale->cash_received !== null)
                    PHP {{ number_format($sale->change_amount, 2) }}
                @else
                    Not recorded
                @endif
            </span>
        </div>

    </div>

    <div class="receipt-divider"></div>

    <div class="summary-row receipt-total">
        <span class="summary-label">TOTAL</span>
        <span class="summary-value">
            PHP {{ number_format($sale->total_amount, 2) }}
        </span>
    </div>

    <div class="receipt-divider"></div>

    <div class="receipt-footer">
        Thank you for shopping at<br>
        SUKLI POS!
    </div>

</div>
</body>
</html>
