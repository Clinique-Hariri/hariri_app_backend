<?php

namespace Modules\Orders\Constants;

use Illuminate\Support\Collection;

class OrderStatus
{
    const PENDING = 'pending';
    const ACCEPTED = 'accepted';
    const CANCELED = 'canceled';
    const ONGOING = 'ongoing';
    const DELIVERED = 'delivered';

    public static function all($translated = false): array
    {
        return [
            self::PENDING => $translated ? __('orders::app.pending') : self::PENDING,
            self::ACCEPTED => $translated ? __('orders::app.accepted') : self::ACCEPTED,
            self::CANCELED => $translated ? __('orders::app.canceled') : self::CANCELED,
            self::ONGOING => $translated ? __('orders::app.ongoing') : self::ONGOING,
            self::DELIVERED => $translated ? __('orders::app.delivered') : self::DELIVERED,
        ];
    }

    public static function colors(): array
    {
        return [
            self::PENDING => 'warning',
            self::ACCEPTED => 'teal',
            self::CANCELED => 'danger',
            self::ONGOING => 'blue',
            self::DELIVERED => 'success',
        ];
    }

    public static function icons(): array
    {
        return [
            self::PENDING => 'bx bx-time',
            self::ACCEPTED => 'bx bx-check-circle',
            self::CANCELED => 'bx bx-x-circle',
            self::ONGOING => 'bx bx-cycling',
            self::DELIVERED => 'bx bx-package',
        ];
    }

    public static function actions(): array
    {
        return [
            self::PENDING => __('orders::app.mark_order_pending'),
            self::ACCEPTED => __('orders::app.accept_order'),
            self::CANCELED => __('orders::app.cancel_order'),
            self::ONGOING => __('orders::app.start_order_delivery'),
            self::DELIVERED => __('orders::app.mark_order_delivered'),
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
        return self::PENDING;
    }

    public static function next($status): array
    {
        return match($status) {
            self::PENDING => [self::ACCEPTED, self::CANCELED],
            self::ACCEPTED => [self::ONGOING],
            self::ONGOING => [self::DELIVERED],
            self::DELIVERED => [],
            self::CANCELED => [],
            default => []
        };
    }
}

