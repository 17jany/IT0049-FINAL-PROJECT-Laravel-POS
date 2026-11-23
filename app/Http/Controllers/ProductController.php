<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Display and search active products
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $products = Product::query()
            ->where('is_archived', false)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products', 'search'));
    }

    // Show add product form
    public function create()
    {
        return view('products.create');
    }

    // Save new product with optional image
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] =
                $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product added successfully!');
    }

    // Show product details
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    // Show edit form
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // Update product and image
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $newImage = $request->file('image')
                ->store('products', 'public');

            $oldImage = $product->image;
            $validated['image'] = $newImage;

            $product->update($validated);

            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        } else {
            $product->update($validated);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    // Archive product instead of permanently deleting
    public function destroy(Product $product)
    {
        $product->update([
            'is_archived' => true,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product archived successfully!');
    }
}
