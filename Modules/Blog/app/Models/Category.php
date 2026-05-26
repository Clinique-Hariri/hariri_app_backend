<?php

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'blog_categories';

    protected $fillable = [
        'name',
    ];

    public array $translatable = [
        'name',
    ];

    /**
     * Get all blogs in this category (polymorphic).
     */
    public function blogs(): MorphMany
    {
        return $this->morphMany(Blog::class, 'categoriable');
    }

    /**
     * Get display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name;
    }
}
