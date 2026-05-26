<?php

namespace Modules\Blog\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Blog\Http\Resources\BlogResource;
use Modules\Blog\Models\Blog;

class BlogsApiController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get all published blogs with optional filtering.
     */
    public function index(Request $request)
    {
        $query = Blog::with(['authorable', 'categoriable'])
            ->published()
            ->latest();

        // Filter by author (type_id format: author_1, doctor_2)
        if ($request->has('author')) {
            $parts = explode('_', $request->get('author'));
            if (count($parts) === 2) {
                $type = $parts[0] === 'doctor' ? 'Modules\\Doctors\\Models\\Doctor' : 'Modules\\Blog\\Models\\Author';
                $query->where('authorable_type', $type)->where('authorable_id', $parts[1]);
            }
        }

        // Filter by category (type_id format: category_1, speciality_2)
        if ($request->has('category')) {
            $parts = explode('_', $request->get('category'));
            if (count($parts) === 2) {
                $type = $parts[0] === 'speciality' ? 'Modules\\Doctors\\Models\\Speciality' : 'Modules\\Blog\\Models\\Category';
                $query->where('categoriable_type', $type)->where('categoriable_id', $parts[1]);
            }
        }

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->get('search') . '%');
        }

        if ($request->boolean('paginate')) {
            $blogs = $query->paginate($request->get('per_page', 10));
        } else {
            $blogs = $query->limit($request->get('limit', 20))->get();
        }

        return $this->successResponse(
            data: BlogResource::collection($blogs),
        );
    }

    /**
     * Get popular blogs (highest clicks).
     */
    public function popular(Request $request)
    {
        $blogs = Blog::with(['authorable', 'categoriable'])
            ->published()
            ->popular()
            ->limit($request->get('limit', 10))
            ->get();

        return $this->successResponse(
            data: BlogResource::collection($blogs),
        );
    }

    /**
     * Get similar blogs (same author or category).
     */
    public function similar($blogId, Request $request)
    {
        $blog = Blog::published()->findOrFail($blogId);

        $similarBlogs = Blog::with(['authorable', 'categoriable'])
            ->published()
            ->where('id', '!=', $blog->id)
            ->where(function ($query) use ($blog) {
                // Same author
                $query->where(function ($q) use ($blog) {
                    $q->where('authorable_type', $blog->authorable_type)
                      ->where('authorable_id', $blog->authorable_id);
                })
                // Or same category
                ->orWhere(function ($q) use ($blog) {
                    $q->where('categoriable_type', $blog->categoriable_type)
                      ->where('categoriable_id', $blog->categoriable_id);
                });
            })
            ->latest()
            ->limit($request->get('limit', 5))
            ->get();

        return $this->successResponse(
            data: BlogResource::collection($similarBlogs),
        );
    }

    /**
     * Increment blog click count by one.
     */
    public function incrementClick($blogId)
    {
        $blog = Blog::published()->findOrFail($blogId);
        $blog->incrementClicks();

        return $this->successResponse(
            message: __('blog::app.click_recorded'),
            data: ['clicks_count' => $blog->clicks_count],
        );
    }
}
