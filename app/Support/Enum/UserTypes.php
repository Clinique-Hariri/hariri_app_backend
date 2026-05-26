<?php
namespace App\Support\Enum;
class UserTypes
{
  const ADMIN = 'admin';
  const USER = 'user';

  public static function all($translated = false):array
  {
    return [
      self::ADMIN => $translated ? __('user.roles.admin') : self::ADMIN,
      self::USER => $translated ? __('user.roles.user') : self::USER,
    ];
  }

  public static function lists2(): array
  {
    return [
      self::ADMIN => app()->isLocale('ar') ? 'أدمن' : 'Admin',
      self::USER => app()->isLocale('ar') ? 'مستخدم' : 'User',
    ];
  }

  public static function colors():array
  {
    return [
      self::ADMIN => 'primary',
      self::USER => 'warning',
    ];
  }

  public static function lists_arabic()
  {
    return [
      self::ADMIN => 'أدمن',
      self::USER => 'مستخدم'
    ];
  }

  public static function get_arabic_name(string $type):string
  {
    return self::lists_arabic()[$type];
  }

  public static function get_name(string $type):string
  {
    return self::all(true)[$type];
  }

  public static function get_color(string $type):string
  {
    return self::colors()[$type];
  }
}
