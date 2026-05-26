<?php

namespace Modules\Transactions\Constants;

use Illuminate\Support\Collection;

class Status
{
  const PENDING   = 'pending';
  const COMPLETED = 'completed';
  const FAILED    = 'failed';
  const CANCELLED = 'cancelled';
  const REFUNDED  = 'refunded';

  public static function all(bool $translated = false): array
  {
    return [
      self::PENDING   => $translated ? __('transactions::app.pending')   : self::PENDING,
      self::COMPLETED => $translated ? __('transactions::app.completed') : self::COMPLETED,
      self::FAILED    => $translated ? __('transactions::app.failed')    : self::FAILED,
      self::CANCELLED => $translated ? __('transactions::app.cancelled') : self::CANCELLED,
      self::REFUNDED  => $translated ? __('transactions::app.refunded')  : self::REFUNDED,
    ];
  }

  public static function colors(): array
  {
    return [
      self::PENDING   => 'warning',
      self::COMPLETED => 'success',
      self::FAILED    => 'danger',
      self::CANCELLED => 'secondary',
      self::REFUNDED  => 'info',
    ];
  }

  public static function collection(): Collection
  {
    return collect(self::all());
  }

  public static function get(string $status): string
  {
    return self::collection()->get($status, $status);
  }

  public static function get_name(string $status): string
  {
    return self::all(true)[$status] ?? $status;
  }

  public static function get_color(string $status): string
  {
    return self::colors()[$status] ?? 'secondary';
  }

  public static function default(): string
  {
    return self::PENDING;
  }
}
