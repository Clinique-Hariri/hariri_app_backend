@php
  use Illuminate\Support\Facades\Route;
  use App\Support\Enum\UserRoles;
  $configData = Helper::appClasses();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- ! Hide app brand if navbar-full -->
  @if(!isset($navbarFull))
    <div class="app-brand demo">
    <a href="{{url('/admin')}}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img src="{{asset('assets/img/logo/logo2.png')}}" alt="Logo" width="65" height="65"/>
      </span>
      <span class="app-brand-text demo menu-text fw-bold ms-2 text-capitalize">@lang('app-name')</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
    </a>
    </div>
  @endif

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner">
    @foreach ($menuData->verticalMenu as $menu)

      {{-- adding active and open class if child is active --}}

      {{-- menu headers --}}
      @if (isset($menu->menuHeader))
        @if (auth()->user()->hasRole([UserRoles::SUPER_ADMIN, UserRoles::ADMIN]))
        <li class="menu-header small text-uppercase mt-0 mb-0">
        <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
        </li>
        @endif
      @else

        {{-- active menu method --}}
        @php
        $activeClass = null;
        $currentRouteName = Route::currentRouteName();

        if (!isset($menu->submenu)) {
        if (gettype($menu->slug) === 'array') {
        foreach ($menu->slug as $slug) {
        if (str_contains($currentRouteName, $slug) and strpos($currentRouteName, $slug) === 0) {
        $activeClass = 'active';
        }
        }
        } else {
        if (str_contains($currentRouteName, $menu->slug) and strpos($currentRouteName, $menu->slug) === 0) {
        $activeClass = 'active';
        }
        }
        } elseif (isset($menu->submenu)) {
        if (gettype($menu->slug) === 'array') {
        foreach ($menu->slug as $slug) {
        if (str_contains($currentRouteName, $slug) and strpos($currentRouteName, $slug) === 0) {
        $activeClass = 'active open';
        }
        }
        } else {
        if (str_contains($currentRouteName, $menu->slug) and strpos($currentRouteName, $menu->slug) === 0) {
        $activeClass = 'active open';
        }
        }
        }
        @endphp

        {{-- main menu --}}
        @php
        $hasPermission = empty($menu->permission) || (
        is_array($menu->permission)
        ? collect($menu->permission)->some(fn($perm) => auth()->user()?->hasPermissionTo($perm))
        : auth()->user()?->hasPermissionTo($menu->permission)
        );
        @endphp

        @if ($hasPermission)
        <li class="menu-item {{$activeClass}}">
        <a href="{{ isset($menu->route) ? route($menu->route) : 'javascript:void(0);' }}"
        class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
        @isset($menu->icon)
        <i class="{{ $menu->icon }}"></i>
        @endisset
        <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
        </a>
        {{-- submenu --}}
        @isset($menu->submenu)
        @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
        @endisset
        </li>
        @endif
      @endif
  @endforeach
  </ul>

</aside>