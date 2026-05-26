<?php

namespace Modules\Ads\Constants;

use Illuminate\Support\Collection;

class AdType
{
  const NONE = 'none';
  const INTERNAL = 'internal';
  const EXTERNAL = 'external';
  public static function all($translated = false):array
  {
    return [
      self::NONE => $translated ? __('ads::app.none') : self::NONE,
      self::INTERNAL => $translated ? __('ads::app.internal') : self::INTERNAL,
      self::EXTERNAL => $translated ? __('ads::app.external') :self::EXTERNAL,
    ];
  }

  public static function colors(): array
  {
    return [
      self::NONE => 'warning',
      self::INTERNAL => 'success',
      self::EXTERNAL => 'info',
    ];
  }

  public static function collection():Collection
  {
    return collect(array_combine(self::all(), self::all()));
  }

  public static function get(string $gender):string
  {
    return self::collection()->get($gender);
  }

  public static function get_name(string $status):string
  {
    return self::all(true)[$status];
  }

  public static function get_color(string $status):string
  {
    return self::colors()[$status];
  }

  public static function default():string
  {
    return self::NONE;
  }

}
