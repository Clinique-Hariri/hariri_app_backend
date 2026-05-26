<?php

namespace App\Services;

class MenuBuilder
{
  // Static array to hold the menu items
  protected static array $menu = [];

  /**
   * Add a new menu item to the menu.
   *
   * @param string $name The name of the menu item
   * @param array|string $slug The slug(s) for the menu item
   * @param string|null $route The route for the menu item
   * @param string|null $icon The icon class for the menu item
   * @param array|string|null $permission The permission(s) required to access this item
   * @param array $submenu The submenu items, if any
   */
  public static function add(
    string $name,
    array|string $slug,
    ?string $route = null,
    ?string $icon = 'bx bx-circle', // Default icon class (without 'menu-icon tf-icons')
    array|string|null $permission = null,
    array $submenu = []
  ): void {
    $item = [
      'name' => $name,
      'icon' => 'menu-icon tf-icons ' . $icon, // Add the default prefix
      'slug' => $slug,
    ];

    // If permission is provided, add it to the item
    if ($permission) {
      $item['permission'] = $permission;
    }

    // If route is provided, add it to the item
    if ($route) {
      $item['route'] = $route;
    }

    // If submenu is provided, add it to the item
    if (!empty($submenu)) {
      $item['submenu'] = $submenu;
    }

    // Add the item to the menu
    self::$menu[] = $item;
  }

  /**
   * Create a submenu item.
   *
   * @param string $name The name of the submenu item
   * @param string $slug The slug for the submenu item
   * @param string $route The route for the submenu item
   * @param string|null $icon The icon class for the submenu item
   * @param string|array|null $permission The permission required to access the submenu
   * @return array The submenu item
   */
  public static function submenu(
    string $name,
    string $slug,
    string $route,
    ?string $icon = null, // Default icon class (without 'menu-icon tf-icons')
    string|array|null $permission = null
  ): array {
    $sub = [
      'name' => $name,
      'route' => $route,
      'slug' => $slug,
      'icon' => $icon? 'menu-icon tf-icons ' . $icon : '', // Add the default prefix
    ];

    // If permission is provided, add it to the submenu
    if ($permission) {
      $sub['permission'] = $permission;
    }

    return $sub;
  }

  /**
   * Add a header to the menu.
   *
   * @param string $title The title of the header
   */
  public static function header(string $title): void
  {
    self::$menu[] = ['menuHeader' => $title];
  }

  /**
   * Retrieve the current menu.
   *
   * @return array The current menu items
   */
  public static function get(): array
  {
    return self::$menu;
  }
}
