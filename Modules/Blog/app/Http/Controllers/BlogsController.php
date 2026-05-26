<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Enum\PermissionNames;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Blog\Datatables\BlogDatatable;
use Modules\Blog\Http\Requests\CreateBlogRequest;
use Modules\Blog\Http\Requests\EditBlogRequest;
use Modules\Blog\Models\Author;
use Modules\Blog\Models\Blog;
use Modules\Blog\Models\Category;
use Modules\Doctors\Models\Doctor;
use Modules\Doctors\Models\Speciality;

class BlogsController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOGS)) {
            return redirect()->route('unauthorized');
        }

        $datatable = new BlogDatatable();
        if ($request->wantsJson()) {
            return $datatable->datatables($request);
        }
        return view("blog::blog.index")->with([
            "columns" => $datatable::columns(),
        ]);
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOGS)) {
            return redirect()->route('unauthorized');
        }

        $authors = Author::all();
        $categories = Category::all();
        $doctors = Doctor::where('status', 'active')->get();
        $specialities = Speciality::where('status', 'active')->get();

        return view("blog::blog.create-edit")->with([
            "edit" => false,
            "authors" => $authors,
            "categories" => $categories,
            "doctors" => $doctors,
            "specialities" => $specialities,
        ]);
    }

    public function edit($id)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOGS)) {
            return redirect()->route('unauthorized');
        }

        $authors = Author::all();
        $categories = Category::all();
        $doctors = Doctor::where('status', 'active')->get();
        $specialities = Speciality::where('status', 'active')->get();

        return view("blog::blog.create-edit")->with([
            "edit" => true,
            "model" => Blog::findOrFail($id),
            "authors" => $authors,
            "categories" => $categories,
            "doctors" => $doctors,
            "specialities" => $specialities,
        ]);
    }

    public function store(CreateBlogRequest $request)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOGS)) {
            return redirect()->route('unauthorized');
        }

        try {
            $data = $request->validated();

            // Handle background image upload
            if ($request->hasFile('background_image')) {
                $data['background_image'] = $request->file('background_image')->store('blog/posts', 'public');
            }

            // Parse author selection (format: "author_1" or "doctor_5")
            if ($request->filled('author')) {
                [$authorType, $authorId] = explode('_', $request->author, 2);
                if ($authorType === 'author') {
                    $data['authorable_type'] = 'Modules\\Blog\\Models\\Author';
                } else {
                    $data['authorable_type'] = 'Modules\\Doctors\\Models\\Doctor';
                }
                $data['authorable_id'] = $authorId;
            }

            // Parse category selection (format: "category_1" or "speciality_5")
            if ($request->filled('category')) {
                [$categoryType, $categoryId] = explode('_', $request->category, 2);
                if ($categoryType === 'category') {
                    $data['categoriable_type'] = 'Modules\\Blog\\Models\\Category';
                } else {
                    $data['categoriable_type'] = 'Modules\\Doctors\\Models\\Speciality';
                }
                $data['categoriable_id'] = $categoryId;
            }

            // Remove helper fields
            unset($data['author'], $data['category']);

            $model = Blog::create($data);

            return redirect()->route('blogs.index')->with('success', __('blog::app.created_successfully', ['item' => __('blog::app.blog')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(EditBlogRequest $request, $id)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOGS)) {
            return redirect()->route('unauthorized');
        }

        try {
            $model = Blog::findOrFail($id);
            $data = $request->validated();

            // Handle background image upload
            if ($request->hasFile('background_image')) {
                // Delete old image
                if ($model->background_image) {
                    Storage::disk('public')->delete($model->background_image);
                }
                $data['background_image'] = $request->file('background_image')->store('blog/posts', 'public');
            }

            // Parse author selection (format: "author_1" or "doctor_5")
            if ($request->filled('author')) {
                [$authorType, $authorId] = explode('_', $request->author, 2);
                if ($authorType === 'author') {
                    $data['authorable_type'] = 'Modules\\Blog\\Models\\Author';
                } else {
                    $data['authorable_type'] = 'Modules\\Doctors\\Models\\Doctor';
                }
                $data['authorable_id'] = $authorId;
            }

            // Parse category selection (format: "category_1" or "speciality_5")
            if ($request->filled('category')) {
                [$categoryType, $categoryId] = explode('_', $request->category, 2);
                if ($categoryType === 'category') {
                    $data['categoriable_type'] = 'Modules\\Blog\\Models\\Category';
                } else {
                    $data['categoriable_type'] = 'Modules\\Doctors\\Models\\Speciality';
                }
                $data['categoriable_id'] = $categoryId;
            }

            // Remove helper fields
            unset($data['author'], $data['category']);

            $model->update($data);

            return redirect()->route('blogs.index')->with('success', __('blog::app.updated_successfully', ['item' => __('blog::app.blog')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOGS)) {
            return redirect()->route('unauthorized');
        }

        $model = Blog::with(['authorable', 'categoriable'])->findOrFail($id);

        return view("blog::blog.show")->with([
            "model" => $model,
        ]);
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_BLOGS)) {
            return redirect()->route('unauthorized');
        }

        try {
            $model = Blog::findOrFail($id);

            // Delete background image
            if ($model->background_image) {
                Storage::disk('public')->delete($model->background_image);
            }

            $model->delete();

            return redirect()->route('blogs.index')->with('success', __('blog::app.deleted_successfully', ['item' => __('blog::app.blog')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
