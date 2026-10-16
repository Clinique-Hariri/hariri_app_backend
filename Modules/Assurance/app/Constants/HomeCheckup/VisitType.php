<?php

namespace Modules\Assurance\Constants\HomeCheckup;

use App\Models\Setting;
use Illuminate\Support\Collection;

class VisitType
{
  const NURSE_ONLY = 'nurse_only';
  const WITH_DOCTOR = 'with_doctor';

  /**
   * Setting keys holding the default price for each visit type.
   */
  const PRICE_SETTING_KEYS = [
    self::NURSE_ONLY => 'home_checkup_nurse_only_price',
    self::WITH_DOCTOR => 'home_checkup_with_doctor_price',
  ];

  /**
   * Fallback default prices when no setting is configured.
   */
  const DEFAULT_PRICES = [
    self::NURSE_ONLY => 1000.00,
    self::WITH_DOCTOR => 2000.00,
  ];

  public static function all($translated = false): array
  {
    return [
      self::NURSE_ONLY => $translated ? __(key: 'assurance::app.home_checkup.visit_types.nurse_only') : self::NURSE_ONLY,
      self::WITH_DOCTOR => $translated ? __(key: 'assurance::app.home_checkup.visit_types.with_doctor') : self::WITH_DOCTOR,
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

  public static function get_price(string $type): float
  {
    $settingKey = self::PRICE_SETTING_KEYS[$type] ?? null;
    $price = $settingKey ? Setting::getValue($settingKey) : null;

    if ($price === null || $price === '') {
      return (float) (self::DEFAULT_PRICES[$type] ?? 0.00);
    }

    return (float) $price;
  }

  public static function prices(): array
  {
    $prices = [];
    foreach (self::all() as $type => $label) {
      $prices[$type] = self::get_price($type);
    }

    return $prices;
  }
}
