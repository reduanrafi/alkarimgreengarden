<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Product $product): JsonResponse
    {
        $reviews = $product->reviews()
            ->where('status', true)
            ->with('user')
            ->latest()
            ->paginate(10);

        return response()->json([
            'reviews' => $reviews,
            'avg_rating' => $product->avg_rating,
            'reviews_count' => $product->reviews_count,
        ]);
    }

    public function store(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($product->reviews()->where('user_id', auth()->id())->exists()) {
            return response()->json(['message' => 'You have already reviewed this product.'], 422);
        }

        $review = $product->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return response()->json(['message' => 'Review submitted.', 'review' => $review->load('user')], 201);
    }

    public function update(Request $request, Review $review): JsonResponse
    {
        if ($review->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update($validated);

        return response()->json(['message' => 'Review updated.', 'review' => $review->fresh()->load('user')]);
    }

    public function destroy(Review $review): JsonResponse
    {
        if ($review->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $review->delete();

        return response()->json(['message' => 'Review deleted.']);
    }
}
