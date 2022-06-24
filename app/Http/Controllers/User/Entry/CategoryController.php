<?php

namespace App\Http\Controllers\User\Entry;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Entry\Category\CategoryRequest;
use App\Http\Requests\User\Entry\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);
        return inertia('User/Entries/Categories/Show', [
            "entries" => $category
                ->entries()
                ->with(['entryImportBank', 'category'])
                ->orderByDesc('performed_at')
                ->paginate(12),
            "category" => $category,
            "categories" => auth()->user()->categories
        ]);
    }

    public function store(CategoryRequest $request)
    {
        auth()->user()->categories()->create($request->validated());
        return redirect()->back();
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);
        $category->update($request->validated());
        return redirect()->back();
    }

    public function destroy(Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);
        $category->delete();
        return redirect()->back();
    }

    public function destroyAll()
    {
        auth()->user()->categories()->delete();
        return redirect()->back();
    }
}
