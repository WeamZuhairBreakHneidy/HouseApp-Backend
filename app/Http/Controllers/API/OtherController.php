<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiResponseTrait;
use App\Models\Other;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtherController extends Controller
{
    use ApiResponseTrait;

    // ✅ Helper to decode img_url if stored as a string
    protected function decodeImgUrl($other)
    {
        if (is_string($other->img_url)) {
            $decoded = json_decode($other->img_url, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $other->img_url = $decoded;
            }
        }
        return $other;
    }

    public function index(Request $request)
    {
        $query = Other::query();

        // Dynamic Filtering by all available fields
        $filters = [
            'category', 'title', 'location', 'address', 'description',
            'area_distance', 'arealength', 'areawidth', 'floors_number',
            'is_rent', 'is_sell'
        ];

        foreach ($filters as $field) {
            if ($request->filled($field)) {
                if (in_array($field, ['category','title','location','address','description'])) {
                    $query->where($field, 'like', '%' . $request->input($field) . '%');
                } else {
                    $query->where($field, $request->input($field));
                }
            }
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // main_features filter
        if ($request->filled('main_feature')) {
            $query->whereJsonContains('main_features', $request->input('main_feature'));
        }

        // Pagination
        $perPage = $request->input('per_page', 10);
        $others = $query->paginate($perPage);

        $others->getCollection()->transform(function ($item) {
            return $this->decodeImgUrl($item);
        });

        return $this->successResponse('Others retrieved successfully', $others);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q');
        if (!$keyword) {
            return $this->errorResponse('Search keyword is required', null, 400);
        }

        $query = Other::query();
        $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%$keyword%")
                ->orWhere('location', 'like', "%$keyword%")
                ->orWhere('address', 'like', "%$keyword%")
                ->orWhere('description', 'like', "%$keyword%")
                ->orWhereJsonContains('main_features', $keyword);
        });

        $results = $query->paginate($request->input('per_page', 10));
        $results->getCollection()->transform(fn($item) => $this->decodeImgUrl($item));

        return $this->successResponse('Others retrieved successfully', $results);
    }

    public function show(Other $other)
    {
        $other = $this->decodeImgUrl($other);
        return $this->successResponse('Other retrieved successfully', $other);
    }

    public function store(Request $request)
    {
        // ✅ Validate request
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',

            'main_features' => 'nullable|array',
            'main_features.*' => 'string',

            'area_distance' => 'nullable|integer',
            'arealength' => 'nullable|integer',
            'areawidth' => 'nullable|integer',
            'floors_number' => 'nullable|integer',

            'is_rent' => 'nullable|boolean',
            'is_sell' => 'nullable|boolean',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',

            // ✅ Video validation
            'video' => 'nullable|mimetypes:video/mp4,video/quicktime|max:102400',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', $validator->errors(), 422);
        }

        $data = $request->except('images', 'video');

        // ✅ Handle multiple images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('others/images', 'public');
                $imagePaths[] = asset('storage/' . $path);
            }
        }

        // ✅ Handle video with type check
        $videoPath = null;
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $allowedTypes = ['video/mp4', 'video/quicktime'];
            if (!in_array($video->getClientMimeType(), $allowedTypes)) {
                return $this->errorResponse('Invalid video type. Allowed: mp4, mov', null, 422);
            }
            $path = $video->store('others/videos', 'public');
            $videoPath = asset('storage/' . $path);
        }

        $data['img_url'] = [
            'images' => $imagePaths,
            'video' => $videoPath,
        ];

        $other = Other::create($data);
        return $this->successResponse('Other created successfully', $other);
    }

    public function update(Request $request, Other $other)
    {
        $other->update($request->all());
        return $this->successResponse('Other updated successfully', $other);
    }

    public function destroy($id)
    {
        $other = Other::find($id);
        if (!$other) {
            return $this->errorResponse('Other not found', null, 404);
        }

        $other->delete();
        return $this->successResponse('Other deleted successfully', null);
    }
}
