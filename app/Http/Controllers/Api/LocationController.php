<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;

class LocationController extends Controller
{

    public function show($id)
    {
        $location = Location::find($id);
        if (!$location) return ApiResponse::not_found('Location');
        return ApiResponse::response(200, 'Location Retrived Successfully', $location);
    }

    public function store(StoreLocationRequest $request)
    {
        try {
            $request->validated();

            $location = Location::create([
                'user_id' => $request->user()->id,
                'street' => $request->street,
                'building' => $request->building,
                'area' => $request->area,
            ]);

            return ApiResponse::response(200, 'Category Created Successfully', $location);
        } catch (\Exception $e) {
            return ApiResponse::response(500, 'Error creating location: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $location = Location::find($id);
        if (!$location) return ApiResponse::not_found('Location');
        $request->validate([
            'street' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
        ]);
        $location->fill($request->only($location->fillable));
        $location->save();
        return ApiResponse::response(200, 'Location Updated Successfully', $location);
    }

    public function destroy($id)
    {
        $location = Location::find($id);
        if (!$location) return ApiResponse::not_found('Location');
        $location->delete();
        return ApiResponse::response(200, 'Location Deleted Successfully', []);
    }
}
