<?php

namespace Modules\Doctors\Constants\Doctor;

use Illuminate\Support\Collection;

class Status
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const BANNED = 'banned';

    public static function all($translated = false):array
    {
        return [
          self::ACTIVE => $translated? __(key: 'user.statuses.active') : self::ACTIVE,
          self::INACTIVE => $translated? __(key: 'user.statuses.inactive') : self::INACTIVE,
          self::BANNED => $translated? __(key: 'user.statuses.banned') : self::BANNED,
        ];
    }

    public static function colors(): array
    {
        return [
          self::ACTIVE => 'success',
          self::INACTIVE => 'danger',
          self::BANNED => 'warning',
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
