@extends('layouts.app')

@section('title', 'Products')

@section('content')
<section class="products-page">
    <div class="products-heading">
        <p class="eyebrow">INVENTORY MANAGEMENT</p>
        <h1>Products</h1>
        <p class="subtitle">
            Manage your store products, prices, and stock.
        </p>
    </div>

    @if (session('success'))
        <p class="success-message">
            {{ session('success') }}
        </p>
    @endif

    @if (session('error'))
        <p class="product-alert" role="alert">
            {{ session('error') }}
        </p>
    @endif

    @if ($errors->any())
        <div class="product-alert" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="products-layout">

        {{-- LEFT: PRODUCT LIST --}}
        <div class="product-panel">
            <div class="panel-heading">
                <h2>Product List</h2>
                <span>{{ $products->total() }} products</span>
            </div>

            <form method="GET"
                  action="{{ route('products.index') }}"
                  class="product-search">
                <input
                    type="search"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Search products..."
                    aria-label="Search products">

                <button type="submit" class="search-button">
                    Search
                </button>
            </form>

            <div class="product-table-wrap">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    <div class="product-identity">
                                        @if ($product->image)
                                            <img
                                                src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                                class="product-thumb">
                                        @else
                                            <div class="product-placeholder">
                                                📦
                                            </div>
                                        @endif

                                        <div>
                                            <strong>{{ $product->name }}</strong>
                                            <small>
                                                {{ $product->category ?: 'Uncategorized' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    ₱{{ number_format($product->price, 2) }}
                                </td>

                                <td>
                                    {{ $product->stock_quantity }}
                                </td>

                                <td>
                                    @if ($product->stock_quantity == 0)
                                        <span class="stock-badge out-stock">
                                            Out of Stock
                                        </span>
                                    @elseif ($product->stock_quantity <= 10)
                                        <span class="stock-badge low-stock">
                                            Low
                                        </span>
                                    @else
                                        <span class="stock-badge in-stock">
                                            In Stock
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="product-actions">
                                        <a
                                            href="{{ route('products.edit', $product) }}"
                                            class="edit-link">
                                            Edit
                                        </a>

                                        <span class="action-divider">|</span>

                                        <form
                                            action="{{ route('products.destroy', $product) }}"
                                            method="POST"
                                            onsubmit="return confirm('Archive this product?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="archive-link">
                                                Archive
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-message">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="product-pagination">
                {{ $products->links() }}
            </div>
        </div>

        {{-- RIGHT: ADD PRODUCT FORM --}}
        <div class="product-panel add-product-panel">
            <div class="panel-heading">
                <h2>Add Product</h2>
            </div>

            <form
                action="{{ route('products.store') }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="product-field">
                    <label for="name">
                        Product Name <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter product name"
                        required>
                </div>

                <div class="product-field">
                    <label for="category">Category</label>
                    <input
                        type="text"
                        id="category"
                        name="category"
                        value="{{ old('category') }}"
                        placeholder="Enter category">
                </div>

                <div class="product-field">
                    <label for="description">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        placeholder="Product description">{{ old('description') }}</textarea>
                </div>

                <div class="product-field-row">
                    <div class="product-field">
                        <label for="price">
                            Price (₱) <span class="required">*</span>
                        </label>
                        <input
                            type="number"
                            id="price"
                            name="price"
                            step="0.01"
                            min="0"
                            value="{{ old('price') }}"
                            required>
                    </div>

                    <div class="product-field">
                        <label for="stock_quantity">
                            Starting Stock <span class="required">*</span>
                        </label>
                        <input
                            type="number"
                            id="stock_quantity"
                            name="stock_quantity"
                            min="0"
                            step="1"
                            value="{{ old('stock_quantity', 0) }}"
                            required>
                    </div>
                </div>

                <div class="product-field">
                    <label for="image">Product Image</label>
                    <small>JPG or PNG, up to 5 MB.</small>

                    <div class="image-upload-area">
                        <div class="image-preview-box">
                            <img
                                id="imagePreview"
                                alt="Selected product image"
                                style="display: none;">

                            <div id="imagePlaceholder">
                                <span>📦</span>
                                <small>No image selected</small>
                            </div>
                        </div>

                        <div class="image-upload-controls">
                            <label for="image" class="choose-image-button">
                                Choose Image
                            </label>

                            <input
                                type="file"
                                id="image"
                                name="image"
                                accept=".jpg,.jpeg,.png"
                                class="hidden-file-input">

                            <small id="selectedFileName">
                                No file chosen
                            </small>
                        </div>
                    </div>
                </div>

                <div class="product-form-actions">
                    <button
                        type="submit"
                        class="add-product-button">
                        + Add Product
                    </button>

                    <button
                        type="reset"
                        class="cancel-button">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
    .products-page {
        width: min(100%, 1250px);
        margin: 20px auto;
    }

    .products-heading {
        margin-bottom: 22px;
    }

    .products-heading h1 {
        margin: 6px 0;
        font-size: 32px;
    }

    .products-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.8fr) minmax(300px, 1fr);
        gap: 20px;
        align-items: start;
    }

    .product-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 20px;
        min-width: 0;
    }

    .panel-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .panel-heading h2 {
        font-size: 19px;
        margin: 0;
    }

    .panel-heading span {
        font-size: 12px;
        color: #64748b;
    }

    .product-search {
        display: flex;
        gap: 10px;
        margin-bottom: 18px;
    }

    .product-search input {
        flex: 1;
        min-width: 0;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
    }

    .search-button {
        background: #1769c2;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 10px 16px;
        font-weight: 600;
        cursor: pointer;
    }

    .search-button:hover {
        background: #1255a0;
    }

    .product-table-wrap {
        overflow-x: auto;
    }

    .product-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .product-table th,
    .product-table td {
        padding: 12px 9px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .product-table th {
        background: #edf2f7;
        color: #475569;
        font-weight: 600;
        white-space: nowrap;
    }

    .product-identity {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 150px;
    }

    .product-identity strong,
    .product-identity small {
        display: block;
    }

    .product-identity small {
        color: #64748b;
        margin-top: 4px;
    }

    .product-thumb,
    .product-placeholder {
        width: 44px;
        height: 44px;
        border-radius: 7px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .product-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        font-size: 22px;
    }

    .stock-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .in-stock {
        background: #dcfce7;
        color: #166534;
    }

    .low-stock {
        background: #fef3c7;
        color: #92400e;
    }

    .out-stock {
        background: #fee2e2;
        color: #991b1b;
    }

    .product-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .product-actions form {
        margin: 0;
    }

    .edit-link,
    .archive-link {
        color: #1769c2;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
    }

    .action-divider {
        color: #94a3b8;
    }

    .product-field {
        display: flex;
        flex-direction: column;
        gap: 7px;
        margin-bottom: 16px;
    }

    .product-field label {
        font-size: 13px;
        font-weight: 600;
    }

    .required {
        color: #dc2626;
    }

    .product-field input:not([type="file"]),
    .product-field textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        font: inherit;
        box-sizing: border-box;
    }

    .product-field small {
        color: #64748b;
        font-size: 11px;
    }

    .product-field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .image-upload-area {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 8px;
    }

    .image-preview-box {
        width: 110px;
        height: 110px;
        flex-shrink: 0;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #imagePreview {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #imagePlaceholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        color: #64748b;
        text-align: center;
    }

    #imagePlaceholder span {
        font-size: 32px;
    }

    .image-upload-controls {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 0;
    }

    .choose-image-button {
        display: inline-block;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 10px 12px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
    }

    .choose-image-button:hover {
        background: #e2e8f0;
    }

    .hidden-file-input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        overflow: hidden;
    }

    .product-form-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-top: 12px;
    }

    .add-product-button {
        flex: 1;
        background: #1769c2;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        padding: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .add-product-button:hover {
        background: #1255a0;
    }

    .cancel-button {
        background: transparent;
        color: #1769c2;
        border: none;
        padding: 10px;
        cursor: pointer;
        font-weight: 600;
    }

    .product-pagination {
        margin-top: 18px;
    }

    .product-alert {
        color: #991b1b;
        background: #fee2e2;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 16px;
    }

    @media (max-width: 950px) {
        .products-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 550px) {
        .product-field-row {
            grid-template-columns: 1fr;
        }

        .product-panel {
            padding: 14px;
        }

        .image-upload-area {
            flex-wrap: wrap;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const imagePlaceholder = document.getElementById('imagePlaceholder');
    const selectedFileName = document.getElementById('selectedFileName');

    const productForm = imageInput.closest('form');

    let previewUrl = null;

    function clearPreview() {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }

        imagePreview.removeAttribute('src');
        imagePreview.style.display = 'none';
        imagePlaceholder.style.display = 'flex';
        selectedFileName.textContent = 'No file chosen';
    }

    imageInput.addEventListener('change', function () {
        clearPreview();

        const file = this.files[0];

        if (!file) {
            return;
        }

        const allowedTypes = [
            'image/jpeg',
            'image/png'
        ];

        if (!allowedTypes.includes(file.type)) {
            alert('Please select a JPG or PNG image.');
            this.value = '';
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('Image must not exceed 5 MB.');
            this.value = '';
            return;
        }

        previewUrl = URL.createObjectURL(file);

        imagePreview.src = previewUrl;
        imagePreview.style.display = 'block';
        imagePlaceholder.style.display = 'none';
        selectedFileName.textContent = file.name;
    });

    productForm.addEventListener('reset', function () {
        setTimeout(clearPreview, 0);
    });

    window.addEventListener('beforeunload', function () {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
        }
    });
});
</script>
@endsection
