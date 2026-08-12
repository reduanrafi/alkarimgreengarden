<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q'));

        $categories = Faq::active()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->values();

        $query = Faq::active()->ordered();

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                    ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        $faqs = $query->get();
        $activeCategory = (string) $request->query('category');

        return view('faq.index', compact('faqs', 'categories', 'search', 'activeCategory'));
    }
}
