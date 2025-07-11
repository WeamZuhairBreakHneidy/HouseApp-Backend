<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiResponseTrait;
use App\Models\House;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    use ApiResponseTrait;

public function index(Request $request)
{
    $query = House::query();

    // Dynamic Filtering by all available fields
    $filters = [
        'title', 'location', 'address', 'description',
        'rooms_number', 'baths_number', 'floors_number',
        'ground_distance', 'building_age',
        'is_furnitured', 'is_rent', 'is_sell'
    ];

    foreach ($filters as $field) {
        if ($request->filled($field)) {
            if (in_array($field, ['title', 'location', 'address', 'description'])) {
                $query->where($field, 'like', '%' . $request->input($field) . '%');
            } else {
                $query->where($field, $request->input($field));
            }
        }
    }

    // Special handling for price range
    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->input('min_price'));
    }

    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->input('max_price'));
    }

    // main_features array filter
    if ($request->filled('main_feature')) {
        $query->whereJsonContains('main_features', $request->input('main_feature'));
    }

    // Pagination
    $perPage = $request->input('per_page', 10);
    $houses = $query->paginate($perPage);

    return $this->successResponse('Houses retrieved successfully', $houses);
}

public function search(Request $request)
{
    $keyword = $request->input('q'); // `q` is the query keyword

    if (!$keyword) {
        return $this->errorResponse('Search keyword is required', null, 400);
    }

    $query = House::query();

    $query->where(function ($q) use ($keyword) {
        $q->where('title', 'like', "%$keyword%")
            ->orWhere('location', 'like', "%$keyword%")
            ->orWhere('address', 'like', "%$keyword%")
            ->orWhere('description', 'like', "%$keyword%")
            ->orWhereJsonContains('main_features', $keyword);
    });

    $results = $query->paginate($request->input('per_page', 10));

    return $this->successResponse('Search results', $results);
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
