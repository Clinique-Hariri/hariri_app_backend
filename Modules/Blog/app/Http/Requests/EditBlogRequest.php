<?php

namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author' => ['required', 'string', 'regex:/^(author|doctor)_\d+$/'],
            'category' => ['required', 'string', 'regex:/^(category|speciality)_\d+$/'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'background_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'author.regex' => __('blog::app.invalid_author_selection'),
            'category.regex' => __('blog::app.invalid_category_selection'),
        ];
    }
}
