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
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'bedrooms' => 'required|integer',
            'bathrooms' => 'required|integer',
            'area' => 'required|integer',
            'address' => 'required',
        ]);

        $house = House::create($request->all());
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
