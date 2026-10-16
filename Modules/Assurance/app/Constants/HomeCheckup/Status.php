<?php

namespace Modules\Assurance\Constants\HomeCheckup;

use Illuminate\Support\Collection;

class Status
{
  const PENDING = 'pending';
  const APPROVED = 'approved';
  const REJECTED = 'rejected';
  const CANCELLED = 'cancelled';

  public static function all($translated = false): array
  {
    return [
      self::PENDING => $translated ? __(key: 'assurance::app.home_checkup.statuses.pending') : self::PENDING,
      self::APPROVED => $translated ? __(key: 'assurance::app.home_checkup.statuses.approved') : self::APPROVED,
      self::REJECTED => $translated ? __(key: 'assurance::app.home_checkup.statuses.rejected') : self::REJECTED,
      self::CANCELLED => $translated ? __(key: 'assurance::app.home_checkup.statuses.cancelled') : self::CANCELLED,
    ];
  }

  public static function colors(): array
  {
    return [
      self::PENDING => 'warning',
      self::APPROVED => 'success',
      self::REJECTED => 'danger',
      self::CANCELLED => 'secondary',
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
}
