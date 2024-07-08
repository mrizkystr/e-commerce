<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        return response()->json($banners);
    }

    public function show($id)
    {
        $banner = Banner::find($id);
        if ($banner) {
            return response()->json($banner);
        }
        return response()->json(['message' => 'Banner not found'], 404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
            'active' => 'nullable|boolean',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');

        $banner = Banner::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'active' => $request->active ?? true,
        ]);

        return response()->json($banner, 201);
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::find($id);
        if (!$banner) {
            return response()->json(['message' => 'Banner not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
            'active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            Storage::disk('public')->delete($banner->image_path);
            // Store new image
            $imagePath = $request->file('image')->store('banners', 'public');
            $banner->image_path = $imagePath;
        }

        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->start_date = $request->start_date;
        $banner->end_date = $request->end_date;
        $banner->active = $request->active ?? true;
        $banner->save();

        return response()->json($banner);
    }

    public function destroy($id)
    {
        $banner = Banner::find($id);
        if ($banner) {
            Storage::disk('public')->delete($banner->image_path);
            $banner->delete();
            return response()->json(['message' => 'Banner deleted']);
        }
        return response()->json(['message' => 'Banner not found'], 404);
    }
}
