<?php

namespace Modules\Purchases\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Locations\Models\Location;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Supplier extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const string IMAGE = 'image';

    protected $fillable = [
        'name',
        'phone',
        'location_id',
    ];

    public function registerMediaCollections(): void
    {
      $this->addMediaCollection(self::IMAGE)
        ->singleFile();
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function getPurchasesCountAttribute(){
      return $this->purchases()->count();
    }
}
