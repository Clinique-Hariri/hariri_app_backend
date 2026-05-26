<?php

namespace Modules\Orders\Constants;

use Illuminate\Support\Collection;

class OrderType
{
    const ONLINE = 'online';
    const POS = 'pos';

    public static function all($translated = false): array
    {
        return [
            self::ONLINE => $translated ? __('orders::app.online') : self::ONLINE,
            self::POS => $translated ? __('orders::app.pos') : self::POS,
        ];
    }

    public static function colors(): array
    {
        return [
            self::ONLINE => 'primary',
            self::POS => 'info',
        ];
    }

    public static function collection(): Collection
    {
        return collect(array_combine(self::all(), self::all()));
    }

    public static function get(string $type): string
    {
        return self::collection()->get($type);
    }

    public static function get_name(string $type): string
    {
        return self::all(true)[$type];
    }

    public static function get_color(string $type): string
    {
        return self::colors()[$type];
    }

    public static function default(): string
    {
        return self::ONLINE;
    }
}
