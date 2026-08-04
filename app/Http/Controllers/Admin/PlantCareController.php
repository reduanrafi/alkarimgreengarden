<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlantCareGuide;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlantCareController extends Controller
{
    public function index()
    {
        $guides = PlantCareGuide::orderByDesc('updated_at')->paginate(15);

        return view('admin.plant-care.index', compact('guides'));
    }

    public function create()
    {
        return view('admin.plant-care.create');
    }

    public function store(Request $request, ImageUploadService $uploadService)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords' => ['nullable', 'string', 'max:1000'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        $validated['status'] = $request->boolean('status');
        $validated['slug'] = $this->uniqueSlug(Str::slug($validated['title']));

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $uploadService->upload($request->file('cover_image'), null, 'plant-care');
        }

        PlantCareGuide::create($validated);

        return redirect()->route('admin.plant-care.index')
            ->with('success', 'Plant care guide created successfully.');
    }

    public function edit(PlantCareGuide $guide)
    {
        return view('admin.plant-care.edit', compact('guide'));
    }

    public function update(Request $request, PlantCareGuide $guide, ImageUploadService $uploadService)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:plant_care_guides,slug,'.$guide->id],
            'category' => ['nullable', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords' => ['nullable', 'string', 'max:1000'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        $validated['status'] = $request->boolean('status');

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $uploadService->upload($request->file('cover_image'), $guide->cover_image, 'plant-care');
        }

        $guide->update($validated);

        return redirect()->route('admin.plant-care.index')
            ->with('success', 'Plant care guide updated successfully.');
    }

    public function toggle(PlantCareGuide $guide)
    {
        $guide->update(['status' => ! $guide->status]);

        return back()->with('success', $guide->status ? 'Guide published.' : 'Guide unpublished.');
    }

    public function destroy(PlantCareGuide $guide)
    {
        $guide->delete();

        return redirect()->route('admin.plant-care.index')
            ->with('success', 'Plant care guide deleted.');
    }

    private function uniqueSlug(string $slug): string
    {
        $base = $slug ?: 'guide';
        $candidate = $base;
        $i = 2;

        while (PlantCareGuide::where('slug', $candidate)->exists()) {
            $candidate = $base . '-' . $i;
            $i++;
        }

        return $candidate;
    }
}
