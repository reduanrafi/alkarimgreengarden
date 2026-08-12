<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Models\Category;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $imageUpload;

    public function __construct(ImageUploadService $imageUpload)
    {
        $this->imageUpload = $imageUpload;
    }

    public function index(Request $request)
    {
        $query = Category::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('slug', 'LIKE', "%{$search}%");
            });
        }

        $categories = $query->latest()->paginate(15)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->imageUpload->upload($request->file('image'), null, 'categories');
        }

        $data['status'] = $request->boolean('status');

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(StoreCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->imageUpload->upload($request->file('image'), $category->image, 'categories');
        }

        $data['status'] = $request->boolean('status');

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            $category->products()->update(['category_id' => null]);
        }

        $this->imageUpload->delete($category->image);
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully. Its products were uncategorized.');
    }

    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category restored successfully.');
    }

    public function trashed()
    {
        $categories = Category::onlyTrashed()->latest()->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }
}
