<?php

namespace Modules\Ads\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Ads\Constants\Status;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ad extends Model implements HasMedia
{
    use InteractsWithMedia;

    const string Image = 'image';
    
    protected $fillable = [
        'name',
        'type',
        'link',
        'expiration_date',
        'status',
        'user_types',
    ];

    protected function casts(): array
    {
        return [
            'expiration_date' => 'date',
            'user_types' => 'array',
        ];
    }

    protected function scopeActive($query)
    {
        return $query->where('status', Status::ACTIVE)
            ->where('expiration_date', '>=', now());
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::Image)
            ->singleFile();
    }

    public static function factory()
    {
        return \Modules\Ads\database\factories\AdFactory::new();
    }
}
