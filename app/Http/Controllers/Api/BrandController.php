<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        if (count($brands) == 0) return ApiResponse::not_found('Brands');
        return ApiResponse::response(200, 'Brands Retrived Successfully', $brands);
    }

    public function show($id)
    {
        $brand = Brand::find($id);
        if (!$brand) return ApiResponse::not_found('Brand');
        return ApiResponse::response(200, 'Brand Retrived Successfully', $brand);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $brand = Brand::create(['name' => $request->name]);
        return ApiResponse::response(200, 'Brand Created Successfully', $brand);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $brand = Brand::find($id);
        if (!$brand) return ApiResponse::not_found('Brand');
        $brand->update(['name' => $request->name]);
        return ApiResponse::response(200, 'Brand Updated Successfully', $brand);
    }
    public function destroy($id)
    {
        $brand = Brand::find($id);
        if (!$brand) return ApiResponse::not_found('Brand');
        $brand->delete();
        return ApiResponse::response(200, 'Brand Deleted Successfully', []);
    }
}
