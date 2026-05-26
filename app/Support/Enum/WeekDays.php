<?php
namespace App\Support\Enum;
class WeekDays
{
  const SUNDAY = 'sunday';
  const MONDAY = 'monday';
  const TUESDAY = 'tuesday';
  const WEDNESDAY = 'wednesday';
  const THURSDAY = 'thursday';
  const FRIDAY = 'friday';
  const SATURDAY = 'saturday';

  public static function lists($translated = false):array
  {
    return [
      self::SUNDAY => $translated ? __('app.sunday') : self::SUNDAY,
      self::MONDAY => $translated ? __('app.monday') : self::MONDAY,
      self::TUESDAY => $translated ? __('app.tuesday') : self::TUESDAY,
      self::WEDNESDAY => $translated ? __('app.wednesday') : self::WEDNESDAY,
      self::THURSDAY => $translated ? __('app.thursday') : self::THURSDAY,
      self::FRIDAY => $translated ? __('app.friday') : self::FRIDAY,
      self::SATURDAY => $translated ? __('app.saturday') : self::SATURDAY
    ];
  }
  public static function getName($day):string
  {
    return self::lists(true)[$day];
  }
  public static function weekEnds():array
  {
    return [
      self::FRIDAY,
      self::SATURDAY
    ];
  }

  public static function getDayFromNumber($dayNumber):string
  {
    $days = self::lists();
    return $days[array_keys($days)[$dayNumber]];
  }
}
