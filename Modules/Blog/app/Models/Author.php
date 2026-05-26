<?php

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Author extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'blog_authors';

    protected $fillable = [
        'fullname',
        'bio',
    ];

    /**
     * Get all blogs by this author (polymorphic).
     */
    public function blogs(): MorphMany
    {
        return $this->morphMany(Blog::class, 'authorable');
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    /**
     * Get display name.
     */
    public function getAvatarUrlAttribute()
    {
        return $this->getFirstMediaUrl('image');
    }
}
