<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('status', true)->get();

        $query = Product::with('category')->active();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('fabric', 'LIKE', "%{$search}%")
                    ->orWhere('color', 'LIKE', "%{$search}%")
                    ->orWhere('brand', 'LIKE', "%{$search}%");
            });
        }

        if ($categorySlug = $request->get('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $categorySlug));
        }

        if ($brand = $request->get('brand')) {
            $query->where('brand', $brand);
        }

        if ($minPrice = $request->get('min_price')) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice = $request->get('max_price')) {
            $query->where('price', '<=', $maxPrice);
        }

        if ($fabric = $request->get('fabric')) {
            $query->where('fabric', $fabric);
        }

        if ($color = $request->get('color')) {
            $query->where('color', $color);
        }

        $sort = $request->get('sort', 'latest');
        $query->matchSort($sort);

        $products = $query->paginate(12)->withQueryString();

        $brands = Product::active()->whereNotNull('brand')->distinct()->pluck('brand')->sort();
        $fabrics = Product::active()->whereNotNull('fabric')->distinct()->pluck('fabric')->sort();
        $colors = Product::active()->whereNotNull('color')->distinct()->pluck('color')->sort();

        return view('products.index', compact('categories', 'products', 'brands', 'fabrics', 'colors'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $categories = Category::where('status', true)->get();
        $products = Product::with('category')
            ->where('category_id', $category->id)
            ->where('status', true)
            ->latest()
            ->paginate(12);

        $brands = Product::active()->whereNotNull('brand')->distinct()->pluck('brand')->sort();

        return view('products.index', compact('categories', 'products', 'category', 'brands'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'productAttributeValues.attribute', 'productAttributeValues.attributeValue'])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}
