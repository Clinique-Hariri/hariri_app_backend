<?php

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Doctors\Models\Doctor;
use Modules\Doctors\Models\Speciality;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $fillable = [
        'authorable_type',
        'authorable_id',
        'categoriable_type',
        'categoriable_id',
        'background_image',
        'title',
        'content',
        'clicks_count',
        'published_at',
    ];

    protected $casts = [
        'clicks_count' => 'integer',
        'published_at' => 'datetime',
    ];

    /**
     * Get the authorable model (Author or Doctor).
     */
    public function authorable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the categoriable model (Category or Speciality).
     */
    public function categoriable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Check if the blog author is a Doctor.
     */
    public function isAuthorDoctor(): bool
    {
        return $this->authorable_type === 'Modules\\Doctors\\Models\\Doctor';
    }

    /**
     * Check if the blog author is an Author.
     */
    public function isAuthorBlogAuthor(): bool
    {
        return $this->authorable_type === 'Modules\\Blog\\Models\\Author';
    }

    /**
     * Check if the blog category is a Speciality.
     */
    public function isCategorySpeciality(): bool
    {
        return $this->categoriable_type === 'Modules\\Doctors\\Models\\Speciality';
    }

    /**
     * Check if the blog category is a Category.
     */
    public function isCategoryBlogCategory(): bool
    {
        return $this->categoriable_type === 'Modules\\Blog\\Models\\Category';
    }

    /**
     * Get the author name (from Author or Doctor).
     */
    public function getAuthorNameAttribute(): ?string
    {
        if (!$this->authorable) {
            return null;
        }

        return $this->authorable->fullname ?? $this->authorable->name ?? null;
    }

    /**
     * Get the category name (from Category or Speciality).
     */
    public function getCategoryNameAttribute(): ?string
    {
        if (!$this->categoriable) {
            return null;
        }

        return $this->categoriable->name ?? null;
    }

    /**
     * Get background image URL.
     */
    public function getBackgroundImageUrlAttribute(): ?string
    {
        if ($this->background_image) {
            return asset('storage/' . $this->background_image);
        }

        return null;
    }

    /**
     * Check if blog is published.
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->isPast();
    }

    /**
     * Publish the blog.
     */
    public function publish(): void
    {
        $this->update(['published_at' => now()]);
    }

    /**
     * Unpublish the blog.
     */
    public function unpublish(): void
    {
        $this->update(['published_at' => null]);
    }

    /**
     * Increment the click count.
     */
    public function incrementClicks(): void
    {
        $this->increment('clicks_count');
    }

    /**
     * Scope for published blogs.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    /**
     * Scope for draft blogs (not published).
     */
    public function scopeDraft($query)
    {
        return $query->whereNull('published_at');
    }

    /**
     * Scope to order by popularity (clicks).
     */
    public function scopePopular($query)
    {
        return $query->orderByDesc('clicks_count');
    }

    /**
     * Scope to order by latest.
     */
    public function scopeLatest($query)
    {
        return $query->orderByDesc('created_at');
    }
}
