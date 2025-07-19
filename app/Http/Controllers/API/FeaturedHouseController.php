<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiResponseTrait;
use App\Models\FeaturedHouse;
use App\Models\House;
use Illuminate\Http\Request;

class FeaturedHouseController extends Controller
{
    use ApiResponseTrait;

    // list featured houses with pagination
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $featured = FeaturedHouse::with('house')->paginate($perPage);

        $featured->getCollection()->transform(function ($featuredHouse) {
            if ($featuredHouse->house && is_string($featuredHouse->house->img_url)) {
                $decoded = json_decode($featuredHouse->house->img_url, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $featuredHouse->house->img_url = $decoded;
                }
            }
            return $featuredHouse;
        });

        return $this->successResponse('Featured houses retrieved successfully', $featured);
    }

    // store a new featured house
    public function store(Request $request)
    {
        // extra check: no houses in db
        if (House::count() === 0) {
            return $this->errorResponse('No houses available to feature.');
        }

        $request->validate([
            'house_id' => 'required|exists:houses,id',
        ]);

        if (FeaturedHouse::where('house_id', $request->house_id)->exists()) {
            return $this->errorResponse('House is already featured');
        }

        $featured = FeaturedHouse::create([
            'house_id' => $request->house_id,
        ]);

        return $this->successResponse('House added to featured successfully', $featured);
    }

    public function show(FeaturedHouse $featuredHouse)
    {
        $featuredHouse->load('house');
        if ($featuredHouse->house && is_string($featuredHouse->house->img_url)) {
            $decoded = json_decode($featuredHouse->house->img_url, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $featuredHouse->house->img_url = $decoded;
            }
        }
        return $this->successResponse('Featured house retrieved successfully', $featuredHouse);
    }

    public function update(Request $request, FeaturedHouse $featuredHouse)
    {
        $request->validate([
            'house_id' => 'required|exists:houses,id',
        ]);

        $featuredHouse->update([
            'house_id' => $request->house_id,
        ]);

        return $this->successResponse('Featured house updated successfully', $featuredHouse);
    }
public function destroy($id)
{
    // Check if the featured house exists
    $featuredHouse = FeaturedHouse::find($id);
    if (!$featuredHouse) {
        return $this->errorResponse('Featured house not found', null, 404);
    }

    // Delete the featured house
    $featuredHouse->delete();

    return $this->successResponse('Featured house removed successfully');
}

}
