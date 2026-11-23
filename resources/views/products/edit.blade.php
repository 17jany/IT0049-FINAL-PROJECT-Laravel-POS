
@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<section class="edit-product-page">

    <div class="page-heading">
        <p class="eyebrow">INVENTORY MANAGEMENT</p>
        <h1>Edit Product</h1>
        <p class="subtitle">
            Update product information, price, stock, and image.
        </p>
    </div>

    <div class="edit-product-panel">

        @if ($errors->any())
            <div class="product-error" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('products.update', $product) }}"
            method="POST"
            enctype="multipart/form-data"
            id="editProductForm">

            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">
                    Product Name <span class="required">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $product->name) }}"
                    required>
            </div>

            <div class="form-group">
                <label for="category">Category</label>

                <input
                    type="text"
                    id="category"
                    name="category"
                    value="{{ old('category', $product->category) }}">
            </div>

            <div class="form-group">
                <label for="description">Description</label>

                <textarea
                    id="description"
                    name="description"
                    rows="3">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price">
                        Price (₱) <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        id="price"
                        name="price"
                        min="0"
                        max="99999999.99"
                        step="0.01"
                        value="{{ old('price', $product->price) }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="stock_quantity">
                        Stock Quantity <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        id="stock_quantity"
                        name="stock_quantity"
                        min="0"
                        step="1"
                        value="{{ old('stock_quantity', $product->stock_quantity) }}"
                        required>
                </div>
            </div>

            <div class="form-group">
                <label>Product Image</label>
                <p class="image-help">
                    JPG or PNG, maximum 5 MB.
                </p>

                <div class="image-upload-area">
                    <div class="image-preview-box">

                        @if ($product->image)
                            <img
                                id="imagePreview"
                                src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}">
                        @else
                            <img
                                id="imagePreview"
                                alt="Product image preview"
                                style="display:none;">
                        @endif

                        <div
                            id="imagePlaceholder"
                            style="{{ $product->image ? 'display:none;' : '' }}">
                            <span>📦</span>
                            <small>No image selected</small>
                        </div>
                    </div>

                    <div class="image-controls">
                        <label for="image" class="choose-image-button">
                            Choose New Image
                        </label>

                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept=".jpg,.jpeg,.png"
                            class="hidden-file-input">

                        <small id="selectedFileName">
                            {{ $product->image ? 'Current image saved' : 'No file chosen' }}
                        </small>

                        <small class="image-help">
                            Leave empty to keep the current image.
                        </small>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button
                    type="submit"
                    class="update-product-button">
                    Update Product
                </button>

                <a
                    href="{{ route('products.index') }}"
                    class="cancel-button">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</section>

<style>
    .edit-product-page {
        width: min(100%, 850px);
        margin: 20px auto;
    }

    .page-heading {
        margin-bottom: 22px;
    }

    .page-heading h1 {
        font-size: 32px;
        margin: 6px 0;
    }

    .edit-product-panel {
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

    .form-group input:not([type="file"]),
    .form-group textarea {
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        font: inherit;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: 2px solid #bfdbfe;
        border-color: #1769c2;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .required {
        color: #dc2626;
    }

    .image-help {
        color: #64748b;
        font-size: 12px;
        margin: 0;
    }

    .image-upload-area {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-top: 8px;
    }

    .image-preview-box {
        width: 130px;
        height: 130px;
        flex-shrink: 0;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #f8fafc;
        overflow: hidden;
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
        gap: 8px;
        color: #64748b;
        text-align: center;
    }

    #imagePlaceholder span {
        font-size: 34px;
    }

    .image-controls {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .choose-image-button {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
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

    #selectedFileName {
        color: #64748b;
        font-size: 12px;
        overflow-wrap: anywhere;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 26px;
    }

    .update-product-button {
        background: #1769c2;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .update-product-button:hover {
        background: #1255a0;
    }

    .cancel-button {
        color: #1769c2;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
    }

    .product-error {
        background: #fee2e2;
        color: #991b1b;
        border-radius: 7px;
        padding: 12px;
        margin-bottom: 20px;
    }

    @media (max-width: 600px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .image-upload-area {
            flex-wrap: wrap;
        }

        .edit-product-panel {
            padding: 16px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const imagePlaceholder = document.getElementById('imagePlaceholder');
    const selectedFileName = document.getElementById('selectedFileName');

    const originalImage = imagePreview.getAttribute('src');
    const originalFileLabel = selectedFileName.textContent.trim();

    let previewUrl = null;

    function releasePreview() {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }
    }

    function restoreOriginalImage() {
        releasePreview();

        if (originalImage) {
            imagePreview.src = originalImage;
            imagePreview.style.display = 'block';
            imagePlaceholder.style.display = 'none';
        } else {
            imagePreview.removeAttribute('src');
            imagePreview.style.display = 'none';
            imagePlaceholder.style.display = 'flex';
        }

        selectedFileName.textContent = originalFileLabel;
    }

    imageInput.addEventListener('change', function () {
        releasePreview();

        const file = this.files[0];

        if (!file) {
            restoreOriginalImage();
            return;
        }

        const allowedTypes = ['image/jpeg', 'image/png'];

        if (!allowedTypes.includes(file.type)) {
            alert('Please select a JPG or PNG image.');
            this.value = '';
            restoreOriginalImage();
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('Image must not exceed 5 MB.');
            this.value = '';
            restoreOriginalImage();
            return;
        }

        previewUrl = URL.createObjectURL(file);

        imagePreview.src = previewUrl;
        imagePreview.style.display = 'block';
        imagePlaceholder.style.display = 'none';

        selectedFileName.textContent = file.name;
    });

    window.addEventListener('beforeunload', releasePreview);
});
</script>
@endsection
