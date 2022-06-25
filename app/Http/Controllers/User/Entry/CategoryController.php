<?php

namespace App\Http\Controllers\User\Entry;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Entry\Category\CategoryRequest;
use App\Http\Requests\User\Entry\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Traits\HasPagination;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HasPagination;

    public function show(Category $category, Request $request)
    {
        abort_if($category->user_id !== auth()->id(), 403);
        $entries = $category
            ->entries()
            ->with(['entryImportBank', 'category'])
            ->orderByDesc('performed_at')
            ->paginate(12);
        return $this->preventEmptyPage(
            $entries,
            $request->route(),
            ['category' => $category]
        ) ?: inertia('User/Entries/Categories/Show', [
            "entries" => $entries,
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
