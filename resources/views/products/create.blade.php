@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
<section class="table-section product-form">
    <div>
        <p class="eyebrow">PRODUCT MANAGEMENT</p>
        <h1>Add New Product</h1>
        <p class="subtitle">
            Enter the product information below.
        </p>
    </div>

    @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name"
                   value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" id="category" name="category"
                   value="{{ old('category') }}">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description"
                      rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="price">Price (₱)</label>
            <input type="number" id="price" name="price"
                   min="0" step="0.01"
                   value="{{ old('price') }}" required>
        </div>

        <div class="form-group">
            <label for="stock_quantity">Stock Quantity</label>
            <input type="number" id="stock_quantity"
                   name="stock_quantity" min="0"
                   value="{{ old('stock_quantity', 0) }}" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="button compact-button">
                Save Product
            </button>

            <a href="{{ route('products.index') }}"
               class="button compact-button">
                Cancel
            </a>
        </div>
    </form>
</section>

<style>
    .product-form {
        width: min(100%, 650px);
        margin: 18px auto;
        padding: 22px;
    }

    .product-form h1 {
        margin-bottom: 8px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-top: 16px;
    }

    .form-group label {
        font-weight: 600;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font: inherit;
        box-sizing: border-box;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .compact-button {
        padding: 8px 11px;
        font-size: 12px;
        cursor: pointer;
    }
</style>
@endsection
