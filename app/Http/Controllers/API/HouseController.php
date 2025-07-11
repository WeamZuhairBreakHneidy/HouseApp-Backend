<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiResponseTrait;
use App\Models\House;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $houses = House::all();
        return $this->successResponse('Houses retrieved successfully', $houses);
    }

    public function show(House $house)
    {
        return $this->successResponse('House retrieved successfully', $house);
    }

   public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'location' => 'nullable|string|max:255',
        'address' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',

        'rooms_number' => 'nullable|integer',
        'baths_number' => 'nullable|integer',
        'floors_number' => 'nullable|integer',
        'ground_distance' => 'nullable|integer',
        'building_age' => 'nullable|integer',

        'main_features' => 'nullable|array',
        'main_features.*' => 'string',

        'is_furnitured' => 'nullable|boolean',
        'is_rent' => 'nullable|boolean',
        'is_sell' => 'nullable|boolean',

        'img_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $data = $request->except('img_url');

    if ($request->hasFile('img_url')) {
        $path = $request->file('img_url')->store('houses', 'public');
        $data['img_url'] = asset('storage/' . $path);
    }

    $house = House::create($data);

    return $this->successResponse('House created successfully', $house);
}
    
    public function update(Request $request, House $house)
    {
        $house->update($request->all());
        return $this->successResponse('House updated successfully', $house);
    }

    public function destroy(House $house)
    {
        $house->delete();
        return $this->successResponse('House deleted successfully', null);
    }
}
