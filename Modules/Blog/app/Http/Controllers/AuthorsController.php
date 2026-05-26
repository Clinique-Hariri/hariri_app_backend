<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Enum\PermissionNames;
use Illuminate\Http\Request;
use Modules\Blog\Datatables\AuthorDatatable;
use Modules\Blog\Http\Requests\CreateAuthorRequest;
use Modules\Blog\Http\Requests\EditAuthorRequest;
use Modules\Blog\Models\Author;

class AuthorsController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_AUTHORS)) {
            return redirect()->route('unauthorized');
        }

        $datatable = new AuthorDatatable();
        if ($request->wantsJson()) {
            return $datatable->datatables($request);
        }
        return view("blog::author.index")->with([
            "columns" => $datatable::columns(),
        ]);
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_AUTHORS)) {
            return redirect()->route('unauthorized');
        }

        return view("blog::author.create-edit")->with([
            "edit" => false,
        ]);
    }

    public function edit($id)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_AUTHORS)) {
            return redirect()->route('unauthorized');
        }

        return view("blog::author.create-edit")->with([
            "edit" => true,
            "model" => Author::findOrFail($id),
        ]);
    }

    public function store(CreateAuthorRequest $request)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_AUTHORS)) {
            return redirect()->route('unauthorized');
        }

        try {
            $data = $request->validated();
            unset($data['image']);

            $model = Author::create($data);

            // Handle image upload with Spatie Media Library
            if ($request->hasFile('image')) {
                $model->addMediaFromRequest('image')->toMediaCollection('image');
            }

            return redirect()->route('blog.authors.index')->with('success', __('blog::app.created_successfully', ['item' => __('blog::app.author')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(EditAuthorRequest $request, $id)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_AUTHORS)) {
            return redirect()->route('unauthorized');
        }

        try {
            $model = Author::findOrFail($id);
            $data = $request->validated();
            unset($data['image']);

            $model->update($data);

            // Handle image upload with Spatie Media Library
            if ($request->hasFile('image')) {
                $model->clearMediaCollection('image');
                $model->addMediaFromRequest('image')->toMediaCollection('image');
            }

            return redirect()->route('blog.authors.index')->with('success', __('blog::app.updated_successfully', ['item' => __('blog::app.author')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_AUTHORS)) {
            return redirect()->route('unauthorized');
        }

        try {
            $model = Author::findOrFail($id);
            $model->delete();

            return redirect()->route('blog.authors.index')->with('success', __('blog::app.deleted_successfully', ['item' => __('blog::app.author')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
