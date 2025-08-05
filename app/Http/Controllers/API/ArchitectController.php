<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Architect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchitectController extends Controller
{
    // ✅ Helper like HouseController
    protected function decodeImgUrl($architect)
    {
        if (is_string($architect->img_url)) {
            $decoded = json_decode($architect->img_url, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $architect->img_url = $decoded;
            }
        }
        return $architect;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $architects = Architect::paginate($perPage);

        $architects->getCollection()->transform(function ($item) {
            return $this->decodeImgUrl($item);
        });

        return response()->json([
            'status' => true,
            'message' => 'Architects retrieved successfully',
            'data' => $architects,
        ]);
    }

   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'specialization' => 'nullable|string',
        'university' => 'nullable|string',
        'country' => 'nullable|string',
        'city' => 'nullable|string',
        'experience' => 'nullable|string',
        'phone' => 'nullable|string',
        'languages' => 'nullable|string',
        'years_experience' => 'nullable|integer',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $data = $request->except('image');

    // 👤 Handle single profile image
    $imagePath = null;
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('architects/images', 'public');
        $imagePath = asset('storage/' . $path);
    }

    $data['img_url'] = [
        'image' => $imagePath
    ];

    $architect = Architect::create($data);

    return response()->json([
        'status' => true,
        'message' => 'Architect created successfully',
        'data' => $this->decodeImgUrl($architect),
    ]);
}


    public function show(Architect $architect)
    {
        $architect = $this->decodeImgUrl($architect);

        return response()->json([
            'status' => true,
            'message' => 'Architect retrieved successfully',
            'data' => $architect,
        ]);
    }



    public function destroy($id)
{
    $architect = Architect::find($id);

    if (!$architect) {
        return response()->json([
            'status' => false,
            'message' => 'Architect not found',
        ], 404);
    }

    // 🧹 Delete image from storage if exists
    if (is_array($architect->img_url) && isset($architect->img_url['image'])) {
        $imageUrl = $architect->img_url['image'];
        $path = str_replace(asset('storage') . '/', '', $imageUrl);
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    $architect->delete();

    return response()->json([
        'status' => true,
        'message' => 'Architect deleted successfully',
    ]);
}

}
