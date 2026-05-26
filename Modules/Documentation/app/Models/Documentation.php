<?php

namespace Modules\Documentation\Models;

use App\Traits\HasGoogleTranslationTrait;
use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{

  protected $fillable = [
    'key',
    'value',
  ];
}
