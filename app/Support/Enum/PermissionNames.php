<?php
namespace App\Support\Enum;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\select;

class PermissionNames
{
  //dashboard
  const MANAGE_ROLES = 'manage_roles';
  const MANAGE_PERMISSIONS = 'manage_permissions';
  const MANAGE_USERS = 'manage_users';
  const MANAGE_SETTINGS = 'manage_settings';
  const MANAGE_ADS = 'manage_ads';
  const MANAGE_DEPARTMENTS = 'manage_departments';
  const MANAGE_SPECIALITIES = 'manage_specialities';
  const MANAGE_DOCTORS = 'manage_doctors';
  const MANAGE_APPOINTMENTS = 'manage_appointments';
  const MANAGE_PATIENTS = 'manage_patients';
  const MANAGE_SERVICES = 'manage_services';
  const MANAGE_EXAMINATIONS = 'manage_examinations';
  const MANAGE_COUPONS = 'manage_coupons';
  const MANAGE_CATEGORIES ='manage_categories';
  const MANAGE_PRODUCTS = 'manage_products';
  const MANAGE_ORDERS = 'manage_orders';
  const MANAGE_PURCHASES = 'manage_purchases';
  const MANAGE_BLOGS = 'manage_blogs';
  const MANAGE_BLOG_AUTHORS = 'manage_blog_authors';
  const MANAGE_BLOG_CATEGORIES = 'manage_blog_categories';

  public static function all($lang = null):array
  {
    return [
      self::MANAGE_ROLES                => $lang ? __('permissions.'.self::MANAGE_ROLES) : self::MANAGE_ROLES,
      self::MANAGE_PERMISSIONS          => $lang ? __('permissions.'.self::MANAGE_PERMISSIONS) : self::MANAGE_PERMISSIONS,
      self::MANAGE_USERS                => $lang ? __('permissions.'.self::MANAGE_USERS) : self::MANAGE_USERS,
      self::MANAGE_SETTINGS             => $lang ? __('permissions.'.self::MANAGE_SETTINGS) : self::MANAGE_SETTINGS,
      self::MANAGE_ADS                  => $lang ? __('permissions.'.self::MANAGE_ADS) : self::MANAGE_ADS,
      self::MANAGE_DEPARTMENTS          => $lang ? __('permissions.'.self::MANAGE_DEPARTMENTS) : self::MANAGE_DEPARTMENTS,
      self::MANAGE_SPECIALITIES         => $lang ? __('permissions.'.self::MANAGE_SPECIALITIES) : self::MANAGE_SPECIALITIES,
      self::MANAGE_DOCTORS              => $lang ? __('permissions.'.self::MANAGE_DOCTORS) : self::MANAGE_DOCTORS,
      self::MANAGE_APPOINTMENTS         => $lang ? __('permissions.'.self::MANAGE_APPOINTMENTS) : self::MANAGE_APPOINTMENTS,
      self::MANAGE_SERVICES             => $lang ? __('permissions.'.self::MANAGE_SERVICES) : self::MANAGE_SERVICES,
      self::MANAGE_EXAMINATIONS         => $lang ? __('permissions.'.self::MANAGE_EXAMINATIONS) : self::MANAGE_EXAMINATIONS,
      self::MANAGE_COUPONS              => $lang ? __('permissions.'.self::MANAGE_COUPONS) : self::MANAGE_COUPONS,
      self::MANAGE_CATEGORIES           => $lang ? __('permissions.'.self::MANAGE_CATEGORIES) : self::MANAGE_CATEGORIES,
      self::MANAGE_PRODUCTS             => $lang ? __('permissions.'.self::MANAGE_PRODUCTS) : self::MANAGE_PRODUCTS,
      self::MANAGE_ORDERS               => $lang ? __('permissions.'.self::MANAGE_ORDERS) : self::MANAGE_ORDERS,
      self::MANAGE_PATIENTS             => $lang ? __('permissions.'.self::MANAGE_PATIENTS) : self::MANAGE_PATIENTS,
      self::MANAGE_PURCHASES            => $lang ? __('permissions.'.self::MANAGE_PURCHASES) : self::MANAGE_PURCHASES,
      self::MANAGE_BLOGS                => $lang ? __('permissions.'.self::MANAGE_BLOGS) : self::MANAGE_BLOGS,
      self::MANAGE_BLOG_AUTHORS         => $lang ? __('permissions.'.self::MANAGE_BLOG_AUTHORS) : self::MANAGE_BLOG_AUTHORS,
      self::MANAGE_BLOG_CATEGORIES      => $lang ? __('permissions.'.self::MANAGE_BLOG_CATEGORIES) : self::MANAGE_BLOG_CATEGORIES,
    ];
  }

  public static function get_permission_slug($permission): string
  {
    return self::all(session('locale'))[$permission];
  }
}
