<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::paginate(10);
        if (count($products) == 0) return ApiResponse::not_found('Products');
        return ApiResponse::response(200, 'Products Retrived Successfully', $products);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) return ApiResponse::not_found('Product');
        return ApiResponse::response(200, 'Product Retrived Successfully', $product);
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $request->validated();
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('products', $imageName);
            $imageUrl = config('app.url') . '/storage/products/' . $imageName;

            $product = Product::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'discount' => $request->discount,
                'quantity' => $request->quantity,
                'in_stock' => $request->in_stock,
                'image' => $imageUrl,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
            ]);

            return ApiResponse::response(200, 'Product Created Successfully', $product);
        } catch (\Exception $e) {
            return ApiResponse::response(500, 'Error creating product: ' . $e->getMessage());
        }
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = Product::find($id);
            if (!$product) return ApiResponse::not_found('Product');

            $product->update($request->validated());

            if ($request->hasFile('image')) {
                if ($product->image) {
                    $existingImagePath = storage_path('app/public/products/' . basename($product->image));
                    if (file_exists($existingImagePath)) {
                        unlink($existingImagePath);
                    }
                }

                $imageName = time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->storeAs('products', $imageName);
                $product->image = config('app.url') . '/storage/products/' . $imageName;
            }

            $product->save();
            return ApiResponse::response(200, 'Category Updated Successfully', $product);
        } catch (\Exception $e) {
            return ApiResponse::response(500, 'Error updating product: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) return ApiResponse::not_found('Product');
        $product->delete();
        return ApiResponse::response(200, 'Product Deleted Successfully', []);
    }
}
