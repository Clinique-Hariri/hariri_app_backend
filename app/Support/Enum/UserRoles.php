<?php
namespace App\Support\Enum;
class UserRoles
{
  const SUPER_ADMIN = 'super_admin';
  const ADMIN = 'admin';
  const CLINIC_ADMIN = 'clinic_admin';
  const PHARMACY_ADMIN = 'pharmacy_admin';

  public static function all($translated = false):array
  {
    return [
      self::SUPER_ADMIN => $translated ? __('user.roles.super_admin') : self::SUPER_ADMIN,
      self::ADMIN => $translated ? __('user.roles.admin') : self::ADMIN,
      self::CLINIC_ADMIN => $translated ? __('user.roles.clinic_admin') : self::CLINIC_ADMIN,
      self::PHARMACY_ADMIN => $translated ? __('user.roles.pharmacy_admin') : self::PHARMACY_ADMIN,
    ];
  }

  public static function lists2(): array
  {
    return [
      self::SUPER_ADMIN => app()->isLocale('ar') ? 'سوبر أدمن' : 'Super Admin',
      self::ADMIN => app()->isLocale('ar') ? 'أدمن' : 'Admin',
      self::CLINIC_ADMIN => app()->isLocale('ar') ? 'أدمن العيادة' : 'Clinic Admin',
      self::PHARMACY_ADMIN => app()->isLocale('ar') ? 'أدمن الصيدلية' : 'Pharmacy Admin',
    ];
  }

  public static function get_name(string $role):string
  {
    return self::all(true)[$role];
  }
}
