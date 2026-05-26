<?php

namespace Modules\Ads\Constants;

use Illuminate\Support\Collection;

class UserType
{
    const CLINIC = 'clinic';
    const PHARMACY = 'pharmacy';

    public static function all($translated = false): array
    {
        return [
            self::CLINIC => $translated ? __('ads::app.clinic') : self::CLINIC,
            self::PHARMACY => $translated ? __('ads::app.pharmacy') : self::PHARMACY,
        ];
    }

    public static function colors(): array
    {
        return [
            self::CLINIC => 'primary',
            self::PHARMACY => 'success',
        ];
    }

    public static function collection(): Collection
    {
        return collect(array_combine(self::all(), self::all()));
    }

    public static function get(string $userType): string
    {
        return self::collection()->get($userType);
    }

    public static function get_name(string $userType): string
    {
        return self::all(true)[$userType];
    }

    public static function get_color(string $userType): string
    {
        return self::colors()[$userType];
    }

    public static function default()
    {
        return json_encode(self::all());
    }
}
