<?php

namespace App\Http\Controllers;

use App\Models\PlantCareGuide;
use Illuminate\Http\Request;

class PlantCareController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q'));

        $categories = PlantCareGuide::active()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->values();

        $query = PlantCareGuide::active()->orderByDesc('updated_at');

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $guides = $query->paginate(9)->withQueryString();
        $activeCategory = (string) $request->query('category');

        return view('care-guides.index', compact('guides', 'categories', 'search', 'activeCategory'));
    }

    public function show(string $slug)
    {
        $guide = PlantCareGuide::active()->where('slug', $slug)->firstOrFail();

        $related = PlantCareGuide::active()
            ->where('id', '!=', $guide->id)
            ->when($guide->category, fn ($q) => $q->where('category', $guide->category))
            ->orderByDesc('updated_at')
            ->limit(3)
            ->get();

        if ($related->count() < 3 && $guide->category) {
            $fallback = PlantCareGuide::active()
                ->where('id', '!=', $guide->id)
                ->whereNotIn('id', $related->pluck('id'))
                ->orderByDesc('updated_at')
                ->limit(3 - $related->count())
                ->get();

            $related = $related->merge($fallback);
        }

        return view('care-guides.show', compact('guide', 'related'));
    }
}
