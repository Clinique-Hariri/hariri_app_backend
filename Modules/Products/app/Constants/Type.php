<?php

namespace Modules\Products\Constants;

use Illuminate\Support\Collection;

class Type
{
    const MANUAL = 'manual';
    const PURCHASE = 'purchase';
    const RETURN = 'return';
    public static function all($translated = false):array
    {
        return [
          self::MANUAL => $translated ? __('products::app.manual') : self::MANUAL,
          self::PURCHASE => $translated ? __('products::app.purchase') : self::PURCHASE,
          self::RETURN => $translated ? __('products::app.return') : self::RETURN,
        ];
    }


    public static function all2():array
    {
        return [
          self::MANUAL => __('products::app.manual'),
          self::PURCHASE =>  __('products::app.purchase'),
          self::RETURN => __('products::app.return'),
        ];
    }

    public static function colors(): array
    {
        return [
          self::MANUAL => 'success',
          self::PURCHASE => 'info',
          self::RETURN => 'warning',
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
        return self::MANUAL;
    }

}
