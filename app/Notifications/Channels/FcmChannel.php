<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;

class FcmChannel
{
  protected const int BATCH_SIZE = 500;

  protected $messaging;

  public function __construct(Messaging $messaging)
  {
    $this->messaging = $messaging;
  }

  /**
   * @throws MessagingException
   * @throws FirebaseException
   */
  public function send($notifiable, Notification $notification)
  {
    $message = $notification->toFcm($notifiable);
    if (!$message) return;

    $tokens = $this->tokensFor($notifiable);
    if ($tokens->isEmpty()) return;

    $reports = [];

    foreach ($tokens->chunk(self::BATCH_SIZE) as $chunk) {
      $reports[] = $this->messaging->sendAll(
        $chunk->map(fn (string $token) => $message->toToken($token))->values()->all()
      );
    }

    return $reports;
  }

  private function tokensFor($notifiable): Collection
  {
    if (!method_exists($notifiable, 'deviceTokens')) {
      return collect();
    }

    return $notifiable->deviceTokens()->pluck('token');
  }
}
