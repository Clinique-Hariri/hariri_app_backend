<?php

namespace Modules\Blog\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Blog\Models\Author;
use Modules\Doctors\Models\Doctor;
use Modules\Doctors\Models\Speciality;

class BlogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'background_image' => $this->background_image,
            'background_image_url' => $this->background_image_url,
            'clicks_count' => $this->clicks_count,
            'author' => $this->whenLoaded('authorable', function () {
                return new AuthorResource($this->authorable);
            }),
            'category' => $this->whenLoaded('categoriable', function () {
                return new CategoryResource($this->categoriable);
            }),
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
