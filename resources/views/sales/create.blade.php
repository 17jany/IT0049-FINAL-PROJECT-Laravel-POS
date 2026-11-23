@extends('layouts.app')

@section('title', 'Record Sale')

@section('content')
<section class="pos-page">

    <div class="pos-heading">
        <p class="eyebrow">COUNTER / NEW TRANSACTION</p>
        <h1>Record a sale</h1>
        <p class="subtitle">
            Add a product, set the quantity, and record a sale at the counter.
        </p>
    </div>

    @if ($errors->any())
        <div class="pos-error" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="pos-layout">

        {{-- LEFT: RECORD SALE FORM --}}
        <div class="pos-form-panel">
            <form
                action="{{ route('sales.store') }}"
                method="POST"
                id="saleForm">
                @csrf

                <input type="hidden"
                       name="payment_method"
                       value="cash">

                <div class="pos-field">
                    <label for="product_id">
                        Product <span class="required">*</span>
                    </label>
                    <small>Search or select the item to sell.</small>

                    <select id="product_id"
                            name="items[0][product_id]"
                            required>
                        <option value="">Select Product</option>

                        @foreach ($products as $product)
                            <option
                                value="{{ $product->id }}"
                                data-price="{{ $product->price }}"
                                data-stock="{{ $product->stock_quantity }}"
                                @selected(old('items.0.product_id') == $product->id)>
                                {{ $product->name }}
                                (Stock: {{ $product->stock_quantity }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pos-field">
                    <label for="customer_id">Customer (Optional)</label>
                    <small>
                        Link this sale to a customer or select Walk-in.
                    </small>

                    <select id="customer_id" name="customer_id">
                        <option value="">Walk-in Customer</option>

                        @foreach ($customers as $customer)
                            <option
                                value="{{ $customer->id }}"
                                @selected(old('customer_id') == $customer->id)>
                                {{ $customer->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pos-field-row">
                    <div class="pos-field">
                        <label for="cashier">Cashier</label>

                        <input
                            type="text"
                            id="cashier"
                            value="{{ auth()->user()->full_name }}"
                            readonly>
                    </div>

                    <div class="pos-field">
                        <label for="quantity">
                            Quantity <span class="required">*</span>
                        </label>

                        <div class="quantity-control">
                            <button type="button" id="minusQty">−</button>

                            <input
                                type="number"
                                id="quantity"
                                name="items[0][quantity]"
                                min="1"
                                step="1"
                                value="{{ old('items.0.quantity', 1) }}"
                                required>

                            <button type="button" id="plusQty">+</button>
                        </div>
                    </div>
                </div>

                <div class="pos-field">
                    <label for="unitPrice">Unit Price</label>

                    <input
                        type="text"
                        id="unitPrice"
                        value="₱0.00"
                        readonly>
                </div>

                <div class="line-total">
                    <div>
                        <strong>Line Total</strong>
                        <small>Unit price × quantity</small>
                    </div>

                    <strong id="lineTotal">₱0.00</strong>
                </div>

                <div class="pos-field">
                    <label for="cash_received">
                        Cash Received (₱) <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        id="cash_received"
                        name="cash_received"
                        min="0"
                        max="9999999999.99"
                        step="0.01"
                        value="{{ old('cash_received') }}"
                        placeholder="Enter amount received"
                        required>
                </div>

                <div class="change-box">
                    <span>Change (Sukli)</span>
                    <strong id="changeAmount">₱0.00</strong>
                </div>

                <p id="saleWarning"
                   class="sale-warning"
                   role="alert"
                   style="display:none;">
                </p>

                <div class="pos-actions">
                    <button
                        type="submit"
                        id="recordSaleButton"
                        class="record-sale-button"
                        disabled>
                        Record Sale — ₱0.00
                    </button>

                    <a href="{{ route('sales.index') }}"
                       class="cancel-link">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        {{-- RIGHT: RECEIPT PREVIEW --}}
        <div class="receipt-panel">
            <div class="receipt-paper">

                <div class="receipt-header">
                    <h2>SUKLI POS</h2>
                    <p>NEIGHBORHOOD STORE</p>
                    <small>Receipt Preview</small>
                </div>

                <div class="receipt-divider"></div>

                <div class="receipt-info">
                    <p>
                        <span>Date</span>
                        <span id="receiptDate"></span>
                    </p>

                    <p>
                        <span>Cashier</span>
                        <span>{{ auth()->user()->full_name }}</span>
                    </p>

                    <p>
                        <span>Customer</span>
                        <span id="receiptCustomer">
                            Walk-in Customer
                        </span>
                    </p>
                </div>

                <div class="receipt-divider"></div>

                <div class="receipt-items">
                    <div class="receipt-row">
                        <strong>Item</strong>
                        <strong>Qty</strong>
                        <strong>Amount</strong>
                    </div>

                    <div class="receipt-row">
                        <span id="receiptProduct">No product selected</span>
                        <span id="receiptQuantity">0</span>
                        <span id="receiptItemTotal">₱0.00</span>
                    </div>
                </div>

                <div class="receipt-divider"></div>

                <div class="receipt-summary">
                    <p>
                        <span>Subtotal</span>
                        <span id="receiptSubtotal">₱0.00</span>
                    </p>

                    <p>
                        <span>Cash Received</span>
                        <span id="receiptCash">₱0.00</span>
                    </p>

                    <p>
                        <span>Change (Sukli)</span>
                        <span id="receiptChange">₱0.00</span>
                    </p>
                </div>

                <div class="receipt-divider"></div>

                <div class="receipt-total">
                    <strong>TOTAL</strong>
                    <strong id="receiptTotal">₱0.00</strong>
                </div>

                <div class="receipt-divider"></div>

                <p class="receipt-footer">
                    Thank you for shopping at<br>
                    SUKLI POS!
                </p>
            </div>
        </div>
    </div>
</section>

<style>
    .pos-page {
        width: min(100%, 1200px);
        margin: 20px auto;
    }

    .pos-heading {
        margin-bottom: 22px;
    }

    .pos-heading h1 {
        font-size: 32px;
        margin: 6px 0;
    }

    .pos-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(300px, 1fr);
        gap: 22px;
        align-items: start;
    }

    .pos-form-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 24px;
    }

    .pos-field {
        display: flex;
        flex-direction: column;
        gap: 7px;
        margin-bottom: 18px;
    }

    .pos-field label {
        font-size: 14px;
        font-weight: 600;
    }

    .pos-field small {
        color: #64748b;
        font-size: 12px;
    }

    .pos-field input,
    .pos-field select {
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        font: inherit;
        box-sizing: border-box;
        background: #ffffff;
    }

    .pos-field input[readonly] {
        background: #f1f5f9;
    }

    .pos-field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .quantity-control {
        display: flex;
        align-items: stretch;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        overflow: hidden;
    }

    .quantity-control button {
        width: 42px;
        border: none;
        background: #f1f5f9;
        font-size: 20px;
        cursor: pointer;
    }

    .quantity-control input {
        flex: 1;
        min-width: 0;
        text-align: center;
        border: none;
        border-radius: 0;
    }

    .line-total,
    .change-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: #f1f5f9;
        border-radius: 7px;
        margin-bottom: 18px;
    }

    .line-total small {
        display: block;
        margin-top: 5px;
        color: #64748b;
    }

    .line-total strong:last-child,
    .change-box strong {
        font-size: 20px;
    }

    .required {
        color: #dc2626;
    }

    .sale-warning {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        border-radius: 7px;
        padding: 12px;
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 16px;
    }

    .pos-actions {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .record-sale-button {
        flex: 1;
        background: #1769c2;
        color: #ffffff;
        border: none;
        border-radius: 7px;
        padding: 13px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
    }

    .record-sale-button:hover {
        background: #1255a0;
    }

    .record-sale-button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .cancel-link {
        color: #1769c2;
        text-decoration: none;
        font-weight: 600;
    }

    .receipt-panel {
        padding: 0 12px;
    }

    .receipt-paper {
        background: #ffffff;
        padding: 24px 20px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        font-family: 'Courier New', monospace;
        font-size: 12px;
        color: #111827;
    }

    .receipt-header {
        text-align: center;
    }

    .receipt-header h2 {
        font-size: 27px;
        letter-spacing: 2px;
        margin: 0;
    }

    .receipt-header p {
        font-weight: 700;
        letter-spacing: 1px;
        margin: 4px 0;
    }

    .receipt-header small {
        color: #64748b;
    }

    .receipt-divider {
        border-top: 1px dashed #94a3b8;
        margin: 16px 0;
    }

    .receipt-info p,
    .receipt-summary p,
    .receipt-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin: 8px 0;
    }

    .receipt-info span:last-child {
        text-align: right;
        overflow-wrap: anywhere;
    }

    .receipt-items .receipt-row {
        align-items: flex-start;
    }

    .receipt-items .receipt-row span:first-child {
        flex: 1;
    }

    .receipt-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 23px;
    }

    .receipt-footer {
        text-align: center;
        font-weight: 700;
        line-height: 1.7;
    }

    .pos-error {
        background: #fee2e2;
        color: #991b1b;
        border-radius: 7px;
        padding: 12px;
        margin-bottom: 20px;
    }

    @media (max-width: 950px) {
        .pos-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .pos-field-row {
            grid-template-columns: 1fr;
        }

        .pos-form-panel {
            padding: 16px;
        }

        .receipt-panel {
            padding: 0;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const productSelect = document.getElementById('product_id');
    const customerSelect = document.getElementById('customer_id');
    const quantityInput = document.getElementById('quantity');
    const cashInput = document.getElementById('cash_received');
    const minusButton = document.getElementById('minusQty');
    const plusButton = document.getElementById('plusQty');
    const submitButton = document.getElementById('recordSaleButton');
    const warning = document.getElementById('saleWarning');
    const saleForm = document.getElementById('saleForm');

    const money = amount =>
        '₱' + amount.toFixed(2);

    document.getElementById('receiptDate').textContent =
        new Date().toLocaleString('en-PH', {
            dateStyle: 'medium',
            timeStyle: 'short'
        });

    function updateSale() {
        const option = productSelect.selectedOptions[0];

        const price = Number(option?.dataset.price || 0);
        const stock = Number(option?.dataset.stock || 0);
        const quantity = Number(quantityInput.value);
        const cash = Number(cashInput.value || 0);

        const validQuantity =
            Number.isInteger(quantity) &&
            quantity >= 1 &&
            quantity <= stock;

        const total = productSelect.value && validQuantity
            ? price * quantity
            : 0;

        const change = Math.max(0, cash - total);

        quantityInput.max = stock || 1;

        document.getElementById('unitPrice').value =
            money(price);

        document.getElementById('lineTotal').textContent =
            money(total);

        document.getElementById('changeAmount').textContent =
            money(change);

        document.getElementById('receiptProduct').textContent =
            productSelect.value
                ? option.textContent.trim().replace(/\s*\(Stock:.*\)/, '')
                : 'No product selected';

        document.getElementById('receiptQuantity').textContent =
            productSelect.value ? quantity : 0;

        document.getElementById('receiptItemTotal').textContent =
            money(total);

        document.getElementById('receiptSubtotal').textContent =
            money(total);

        document.getElementById('receiptCash').textContent =
            money(cash);

        document.getElementById('receiptChange').textContent =
            money(change);

        document.getElementById('receiptTotal').textContent =
            money(total);

        document.getElementById('receiptCustomer').textContent =
            customerSelect.value
                ? customerSelect.selectedOptions[0].textContent.trim()
                : 'Walk-in Customer';

        let error = '';

        if (!productSelect.value) {
            error = 'Please select a product.';
        } else if (!Number.isInteger(quantity) || quantity < 1) {
            error = 'Please enter a valid quantity.';
        } else if (quantity > stock) {
            error = 'Insufficient stock! Please reduce the quantity.';
        } else if (cashInput.value === '' || cash < total) {
            error = 'Please enter sufficient cash received.';
        }

        warning.textContent = error;
        warning.style.display = error ? 'block' : 'none';

        submitButton.disabled = Boolean(error);
        submitButton.textContent =
            'Record Sale — ' + money(total);
    }

    minusButton.addEventListener('click', function () {
        quantityInput.value = Math.max(
            1,
            Number(quantityInput.value || 1) - 1
        );

        updateSale();
    });

    plusButton.addEventListener('click', function () {
        const stock = Number(
            productSelect.selectedOptions[0]?.dataset.stock || 0
        );

        quantityInput.value = Math.min(
            stock || 1,
            Number(quantityInput.value || 0) + 1
        );

        updateSale();
    });

    productSelect.addEventListener('change', updateSale);
    customerSelect.addEventListener('change', updateSale);
    quantityInput.addEventListener('input', updateSale);
    cashInput.addEventListener('input', updateSale);

    saleForm.addEventListener('submit', function (event) {
        updateSale();

        if (submitButton.disabled) {
            event.preventDefault();
        }
    });

    updateSale();
});
</script>
@endsection
