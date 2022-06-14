<?php

namespace App\Http\Controllers\User\Entry;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Entry\Category\CategoryRequest;
use App\Http\Requests\User\Entry\Category\UpdateCategoryRequest;
use App\Models\Category;

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

    public function update(UpdateCategoryRequest $request)
    {
        $category = Category::find($request->get('category_id'));
        abort_if($category->user_id !== auth()->id(), 403);
        $data = $request->validated();
        unset($data['category_id']);
        $category->update($data);
        return redirect()->back();
    }

    public function delete(Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 403);
        $category->delete();
        return redirect()->back();
    }
}
