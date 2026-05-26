<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Enum\PermissionNames;
use Illuminate\Http\Request;
use Modules\Blog\Datatables\CategoryDatatable;
use Modules\Blog\Http\Requests\CreateCategoryRequest;
use Modules\Blog\Http\Requests\EditCategoryRequest;
use Modules\Blog\Models\Category;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_CATEGORIES)) {
            return redirect()->route('unauthorized');
        }

        $datatable = new CategoryDatatable();
        if ($request->wantsJson()) {
            return $datatable->datatables($request);
        }
        return view("blog::category.index")->with([
            "columns" => $datatable::columns(),
        ]);
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_CATEGORIES)) {
            return redirect()->route('unauthorized');
        }

        return view("blog::category.create-edit")->with([
            "edit" => false,
        ]);
    }

    public function edit($id)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_CATEGORIES)) {
            return redirect()->route('unauthorized');
        }

        return view("blog::category.create-edit")->with([
            "edit" => true,
            "model" => Category::findOrFail($id),
        ]);
    }

    public function store(CreateCategoryRequest $request)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_CATEGORIES)) {
            return redirect()->route('unauthorized');
        }

        try {
            $data = $request->validated();

            $model = Category::create($data);

            return redirect()->route('blog.categories.index')->with('success', __('blog::app.created_successfully', ['item' => __('blog::app.category')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(EditCategoryRequest $request, $id)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_CATEGORIES)) {
            return redirect()->route('unauthorized');
        }

        try {
            $model = Category::findOrFail($id);
            $data = $request->validated();

            $model->update($data);

            return redirect()->route('blog.categories.index')->with('success', __('blog::app.updated_successfully', ['item' => __('blog::app.category')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_CATEGORIES)) {
            return redirect()->route('unauthorized');
        }

        try {
            $model = Category::findOrFail($id);
            $model->delete();

            return redirect()->route('blog.categories.index')->with('success', __('blog::app.deleted_successfully', ['item' => __('blog::app.category')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
