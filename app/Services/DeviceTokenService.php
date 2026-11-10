<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\User;

class DeviceTokenService
{
  /**
   * Register a device token for the user. Tokens are device-scoped, so an
   * existing token is transferred to the user registering it.
   */
  public function register(User $user, string $token, ?string $platform = null): DeviceToken
  {
    $attributes = ['user_id' => $user->id];

    if ($platform !== null) {
      $attributes['platform'] = $platform;
    }

    return DeviceToken::updateOrCreate(['token' => $token], $attributes);
  }

  public function unregister(User $user, string $token): bool
  {
    return $user->deviceTokens()->where('token', $token)->delete() > 0;
  }
}
