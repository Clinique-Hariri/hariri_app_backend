<?php

namespace Modules\Orders\Constants;

use Illuminate\Support\Collection;

class DriverStatus
{
    const FREE = 'free';
    const BUSY = 'busy';

    public static function all($translated = false): array
    {
        return [
            self::FREE => $translated ? __('orders::app.free') : self::FREE,
            self::BUSY => $translated ? __('orders::app.busy') : self::BUSY,
        ];
    }

    public static function colors(): array
    {
        return [
            self::BUSY => 'warning',
            self::FREE => 'info',
        ];
    }

    public static function collection(): Collection
    {
        return collect(array_combine(self::all(), self::all()));
    }

    public static function get(string $status): string
    {
        return self::collection()->get($status);
    }

    public static function get_name(string $status): string
    {
        return self::all(true)[$status];
    }

    public static function get_color(string $status): string
    {
        return self::colors()[$status];
    }

    public static function default(): string
    {
        return self::FREE;
    }
}
