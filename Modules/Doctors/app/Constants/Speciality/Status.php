<?php

namespace Modules\Doctors\Constants\Speciality;

use Illuminate\Support\Collection;

class Status
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    public static function all($translated = false )
    {
        return [
          self::ACTIVE => $translated ? __('doctors::app.active') : self::ACTIVE,
          self::INACTIVE => $translated ? __('doctors::app.inactive') : self::INACTIVE,
        ];
    }

    public static function colors(): array
    {
        return [
          self::ACTIVE => 'success',
          self::INACTIVE => 'danger',
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
        return self::ACTIVE;
    }

}
