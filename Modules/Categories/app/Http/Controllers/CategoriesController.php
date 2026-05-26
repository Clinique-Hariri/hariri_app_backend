<?php

namespace Modules\Categories\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Enum\PermissionNames;
use Illuminate\Http\Request;
use Modules\Categories\Http\Requests\CreateCategoryRequest;
use Modules\Categories\Datatables\CategoryDatatable;
use Modules\Categories\Http\Requests\EditCategoryRequest;
use Modules\Categories\Models\Category;

class CategoriesController extends Controller
{
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_CATEGORIES)) {
      return redirect()->route('unauthorized');
    }

    $datatable = new CategoryDatatable();
    if ($request->wantsJson()) {
      return $datatable->datatables($request);
    }
    return view("categories::index")->with([
      "columns" => $datatable::columns(),
    ]);
  }

  public function create()
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_CATEGORIES)) {
      return redirect()->route('unauthorized');
    }
    return view("categories::create-edit")->with(["edit" => false]);
  }

  public function edit($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_CATEGORIES)) {
      return redirect()->route('unauthorized');
    }
    return view("categories::create-edit")->with(["edit" => true, "model" => Category::findOrFail($id)]);
  }

  public function store(CreateCategoryRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_CATEGORIES)) {
      return redirect()->route('unauthorized');
    }

    try {
      $model = Category::create($request->validated());
      if ($request->hasFile(Category::IMAGE)) {
        storeWebPWithSpatie($model, $request->file(Category::IMAGE), Category::IMAGE);
      }
      return redirect()->route('categories.index')->with('success', __('app.created_successfully', ['name' => __('categories::app.category')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function update(EditCategoryRequest $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_CATEGORIES)) {
      return redirect()->route('unauthorized');
    }
    try {
      $model = Category::findOrFail($id);
      $model->update($request->validated());

      if ($request->hasFile(Category::IMAGE)) {
        $model->clearMediaCollection(Category::IMAGE);
        storeWebPWithSpatie($model, $request->file(Category::IMAGE), Category::IMAGE);
      }
      return redirect()->route('categories.index')->with('success', __('app.updated_successfully', ['name' => __('categories::app.category')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function destroy($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_CATEGORIES)) {
      return redirect()->route('unauthorized');
    }
    try {
      $model = Category::findOrFail($id);
      $model->clearMediaCollection(Category::IMAGE);
      $model->delete();
      return redirect()->route('categories.index')->with('success', __('app.deleted_successfully', ['name' => __('categories::app.category')]));

    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
