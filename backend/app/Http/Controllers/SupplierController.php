<?php

// app/Http/Controllers/SupplierController.php
namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return Supplier::with('products')->paginate(10);
    }

    public function getSupplierDetails(Request $request)
    {
        // Get the search and pagination parameters
        $search = $request->input('search', ''); // Search query
        $pageSize = $request->input('pageSize', 10); // Number of items per page

        // Query suppliers with search filtering on supplier_name or mobile_numbers
        $suppliersQuery = Supplier::query()
            ->where(function ($query) use ($search) {
                $query->where('supplier_name', 'like', '%' . $search . '%')
                    ->orWhere('mobile_numbers', 'like', '%' . $search . '%');
            });

        // Paginate the query result
        $suppliers = $suppliersQuery->paginate($pageSize);

        // Return the paginated supplier data
        return response()->json($suppliers);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'supplier_name' => 'required|string',
            'contact_person' => 'required|string',
            'mobile_numbers' => 'required|string',
            'products' => 'required|array',
        ]);

        // Create or update the supplier
        $supplier = Supplier::updateOrCreate(
            ['id' => $request->input('supplier_id')], // You should pass the supplier_id if editing
            [
                'supplier_name' => $validated['supplier_name'],
                'contact_person' => $validated['contact_person'],
                'mobile_numbers' => $validated['mobile_numbers'],
            ]
        );

        // Get existing products
        $existingProducts = $supplier->products()->pluck('id')->toArray();

        // Loop through products and create or update them
        foreach ($validated['products'] as $productData) {
            if (isset($productData['id']) && in_array($productData['id'], $existingProducts)) {
                // Update existing product
                $product = Product::find($productData['id']);
                if ($product) {
                    $product->update($productData);
                }
            } else {
                // Create new product
                $supplier->products()->create($productData);
            }
        }

        return response()->json($supplier, 201);
    }

    // public function update(Request $request, $id)
    // {

    //     // Fetch the supplier by ID
    //     $supplier = Supplier::findOrFail($id);

    //     // Validate the request data
    //     $validated = $request->validate([
    //         'supplier_name' => 'sometimes|required|string',
    //         'contact_person' => 'sometimes|required|string',
    //         'mobile_numbers' => 'sometimes|required|string',
    //         'products' => 'sometimes|required|array',
    //         'products.*.id' => 'sometimes|integer', // Allow optional ID for products
    //         'products.*.product_name' => 'required|string',
    //         'products.*.product_price' => 'required|numeric',
    //     ]);

    //     // Prepare data for supplier update
    //     $supplierData = array_filter($validated, function ($key) {
    //         return in_array($key, ['supplier_name', 'contact_person', 'mobile_numbers']);
    //     }, ARRAY_FILTER_USE_KEY);

    //     // Update the supplier's general details if any are provided
    //     if (!empty($supplierData)) {
    //         $supplier->update($supplierData);
    //     }

    //     // Check if products are provided in the request
    //     if (isset($validated['products'])) {
    //         foreach ($validated['products'] as $productData) {
    //             if (isset($productData['id'])) {
    //                 // Update existing product
    //                 $product = Product::find($productData['id']);
    //                 if ($product) {
    //                     $product->update($productData);
    //                 }
    //             } else {
    //                 // Create a new product if no ID exists
    //                 $supplier->products()->create($productData);
    //             }
    //         }
    //     }

    //     // Return the updated supplier with a JSON response
    //     return response()->json($supplier->load('products')); // Load related products if needed
    // }

    public function update(Request $request, $id)
    {
        // Fetch the supplier by ID
        $supplier = Supplier::findOrFail($id);

        // Validate the request data
        $validated = $request->validate([
            'supplier_name' => 'sometimes|required|string',
            'contact_person' => 'sometimes|required|string',
            'mobile_numbers' => 'sometimes|required|string',
            'products' => 'sometimes|required|array',
            'products.*.id' => 'sometimes|integer', // Allow optional ID for products
            'products.*.product_name' => 'required|string',
            'products.*.product_price' => 'required|numeric',
        ]);

        // Prepare data for supplier update
        $supplierData = array_filter($validated, function ($key) {
            return in_array($key, ['supplier_name', 'contact_person', 'mobile_numbers']);
        }, ARRAY_FILTER_USE_KEY);

        // Update the supplier's general details if any are provided
        if (!empty($supplierData)) {
            $supplier->update($supplierData);
        }

        // Check if products are provided in the request
        if (isset($validated['products'])) {
            foreach ($validated['products'] as $productData) {
                if (isset($productData['id'])) {
                    // Update existing product
                    $product = Product::find($productData['id']);
                    if ($product) {
                        $product->update($productData);
                    }
                } else {
                    // Create a new product if no ID exists
                    $supplier->products()->create($productData);
                }
            }
        }

        // Return the updated supplier with a JSON response
        return response()->json($supplier->load('products')); // Load related products if needed
    }


    public function destroy($id)
    {
        // Find the supplier by ID
        $supplier = Supplier::find($id);

        // Check if the supplier exists
        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        // Delete the supplier
        $supplier->delete();

        // Return a 204 No Content response
        return response()->json(null, 204);
    }

    public function show($id)
    {
        // Fetch supplier with products for editing
        $supplier = Supplier::with('products')->find($id);
        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }
        return response()->json($supplier);
    }
}
