@extends('layouts.app')

@section('title', 'Add New Customer')

@section('content')
<section class="customer-form-page">

    <div class="page-heading">
        <p class="eyebrow">CUSTOMER MANAGEMENT</p>
        <h1>Add New Customer</h1>
        <p class="subtitle">
            Enter the new customer's information.
        </p>
    </div>

    <div class="customer-form-panel">
        @if ($errors->any())
            <div class="customer-error" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customers.store') }}"
              method="POST" id="customerForm">
            @csrf

            <div class="form-group">
                <label for="full_name">
                    Full Name <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    value="{{ old('full_name') }}"
                    maxlength="100"
                    placeholder="Enter customer full name"
                    required>
            </div>

            <div class="form-group">
                <label for="email">
                    Email Address <span class="required">*</span>
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    maxlength="100"
                    placeholder="Enter email address"
                    required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number (Optional)</label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    maxlength="20"
                    placeholder="Enter contact number">
            </div>

            <div class="form-group">
                <label for="customer_type">
                    Customer Type <span class="required">*</span>
                </label>
                <select id="customer_type"
                        name="customer_type"
                        required>
                    <option value="suki"
                        @selected(old('customer_type', 'suki') === 'suki')>
                        Suki
                    </option>
                    <option value="credit"
                        @selected(old('customer_type') === 'credit')>
                        Credit
                    </option>
                </select>
                <small>
                    Select Credit for customers with an approved credit limit.
                </small>
            </div>

            <div class="form-group" id="creditLimitGroup">
                <label for="credit_limit">
                    Credit Limit (₱) <span class="required">*</span>
                </label>
                <input
                    type="number"
                    id="credit_limit"
                    name="credit_limit"
                    min="0"
                    max="9999999999.99"
                    step="0.01"
                    value="{{ old('credit_limit', 0) }}"
                    placeholder="0.00">
                <small>
                    Maximum credit amount allowed for this customer.
                </small>
            </div>

            <div class="form-actions">
                <button type="submit" class="save-customer-button">
                    Save Customer
                </button>

                <a href="{{ route('customers.index') }}"
                   class="cancel-button">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</section>

<style>
    .customer-form-page {
        width: min(100%, 650px);
        margin: 20px auto;
    }

    .page-heading {
        margin-bottom: 22px;
    }

    .page-heading h1 {
        font-size: 30px;
        margin: 6px 0;
    }

    .customer-form-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 26px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
        margin-bottom: 18px;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        font: inherit;
        box-sizing: border-box;
        background: #ffffff;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: 2px solid #bfdbfe;
        border-color: #1769c2;
    }

    .form-group small {
        font-size: 12px;
        color: #64748b;
    }

    .required {
        color: #dc2626;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 24px;
    }

    .save-customer-button {
        background: #1769c2;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .save-customer-button:hover {
        background: #1255a0;
    }

    .cancel-button {
        color: #1769c2;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
    }

    .customer-error {
        background: #fee2e2;
        color: #991b1b;
        padding: 12px;
        border-radius: 7px;
        margin-bottom: 20px;
    }

    @media (max-width: 600px) {
        .customer-form-panel {
            padding: 18px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const customerType = document.getElementById('customer_type');
    const creditLimitGroup = document.getElementById('creditLimitGroup');
    const creditLimit = document.getElementById('credit_limit');

    function updateCreditFields() {
        const isCredit = customerType.value === 'credit';

        creditLimitGroup.style.display = isCredit ? 'flex' : 'none';
        creditLimit.disabled = !isCredit;
        creditLimit.required = isCredit;

        if (!isCredit) {
            creditLimit.value = '0';
        }
    }

    customerType.addEventListener('change', updateCreditFields);
    updateCreditFields();
});
</script>
@endsection
