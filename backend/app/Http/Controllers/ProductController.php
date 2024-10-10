<?php

// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Display a listing of the products for a specific supplier
    public function index(Supplier $supplier)
    {
        return response()->json($supplier->products()->paginate(10));
    }

    // Store a newly created product in storage
    public function store(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'product_name' => 'required|string',
            'product_price' => 'required|numeric',
            'image' => 'nullable|image|max:2048', // Image is optional, max size 2MB
        ]);

        // Handle the image upload if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $validated['image'] = $imagePath;
        }

        $product = $supplier->products()->create($validated);
        return response()->json($product, 201);
    }

    // Update the specified product in storage
    public function update(Request $request, Supplier $supplier, Product $product)
    {
        $validated = $request->validate([
            'product_name' => 'sometimes|required|string',
            'product_price' => 'sometimes|required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle the image upload if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);
        return response()->json($product);
    }

    // Remove the specified product from storage
    public function destroy(Supplier $supplier, Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
