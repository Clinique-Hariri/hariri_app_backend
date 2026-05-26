<?php

namespace Modules\Doctors\Constants\Appointment;

use Illuminate\Support\Collection;

class Status
{
    const PENDING = 'pending';
    const CONFIRMED = 'confirmed';
    const CANCELLED = 'cancelled';
    const COMPLETED = 'completed';

    public static function all($translated = false):array
    {
        return [
          self::PENDING => $translated? __(key: 'doctors::app.pending') : self::PENDING,
          self::CONFIRMED => $translated? __(key: 'doctors::app.confirmed') : self::CONFIRMED,
          self::CANCELLED => $translated? __(key: 'doctors::app.cancelled') : self::CANCELLED,
          self::COMPLETED => $translated? __(key: 'doctors::app.completed') : self::COMPLETED,
        ];
    }

    public static function colors(): array
    {
        return [
          self::PENDING => 'warning',
          self::CONFIRMED => 'info',
          self::CANCELLED => 'danger',
          self::COMPLETED => 'success',
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
        return self::PENDING;
    }

}
