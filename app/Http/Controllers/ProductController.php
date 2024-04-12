<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use App\Models\Product; // Import the Product model

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create()
    {
        // You can add logic for creating a product here, or simply redirect to a form for creating products
        return view('products.create');
    }
    public function show()
    {
        $products = DB::table('products')->get();
        return view('products', compact('products'));
    }
    public function store(Request $request)
    {
        // Validation rules can be added here
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ], [
            'price.regex' => 'Please enter a valid price with up to two decimal places.',
        ]);
        

        // Create the product
        Product::create($validatedData);

        // Redirect back to the product index page or any other page as needed
        return redirect()->route('products')->with('success', 'Product created successfully');
    }
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
        ]);

        $product->update($validatedData);

        return redirect()->route('products')->with('success', 'Product updated successfully');
    }
    function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products')->with('success', 'Product deleted successfully');
    }
}
