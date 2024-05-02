<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $Categories = Category::all();
        if (count($Categories) == 0) return ApiResponse::not_found('Categories');
        return ApiResponse::response(200, 'Categories Retrived Successfully', $Categories);
    }

    public function show($id)
    {
        $Category = Category::find($id);
        if (!$Category) return ApiResponse::not_found('Category');
        return ApiResponse::response(200, 'Category Retrived Successfully', $Category);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('images', $imageName);
            $imageUrl = config('app.url') . '/storage/images/' . $imageName;

            $Category = Category::create(['name' => $request->name, 'image' => $imageUrl]);


            return ApiResponse::response(200, 'Category Created Successfully', $Category);
        } catch (\Exception $e) {
            return ApiResponse::response(500, 'Error creating category: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if ($request->hasFile('image')) {
            $Category = Category::find($id);
            if (!$Category) return ApiResponse::not_found('Category');
            $request->validate([
                'name' => 'required_if:image,null|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('images', $imageName);
            $imageUrl = config('app.url') . '/storage/images/' . $imageName;

            $Category->update(['name' => $request->name, 'image' => $imageUrl]);
        } else {
            $Category = Category::find($id);
            if (!$Category) return ApiResponse::not_found('Category');
            $Category->update(['name' => $request->name]);
        }
        return ApiResponse::response(200, 'Category Updated Successfully', $Category);
    }

    public function destroy($id)
    {
        $Category = Category::find($id);
        if (!$Category) return ApiResponse::not_found('Category');
        $Category->delete();
        return ApiResponse::response(200, 'Category Deleted Successfully', []);
    }
}
