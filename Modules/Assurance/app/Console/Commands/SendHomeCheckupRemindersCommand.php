<?php

namespace Modules\Assurance\Console\Commands;

use App\Constants\NotificationMessages;
use App\Notifications\NewMessageNotification;
use Illuminate\Console\Command;
use Modules\Assurance\Constants\HomeCheckup\Status;
use Modules\Assurance\Models\HomeCheckup;

class SendHomeCheckupRemindersCommand extends Command
{
  protected $signature = 'assurance:send-home-checkup-reminders';

  protected $description = 'Send reminder notifications for home checkups scheduled within the next 2 hours';

  public function handle(): int
  {
    $now = now();
    $inTwoHours = now()->addHours(2);

    $homeCheckups = HomeCheckup::query()
      ->where('status', Status::APPROVED)
      ->whereNotNull('scheduled_at')
      ->whereNull('reminder_sent_at')
      ->whereBetween('scheduled_at', [$now, $inTwoHours])
      ->with('user')
      ->get();

    foreach ($homeCheckups as $homeCheckup) {
      $homeCheckup->user?->notify(new NewMessageNotification(
        key: NotificationMessages::HOME_CHECKUP_REMINDER,
        data: [
          'type' => 'home_checkups',
          'home_checkup_id' => $homeCheckup->id,
          'scheduled_at' => $homeCheckup->scheduled_at?->toDateTimeString(),
        ],
        channels: ['database', 'fcm'],
        replace: [
          'date' => $homeCheckup->scheduled_at?->format('Y-m-d'),
          'time' => $homeCheckup->scheduled_at?->format('H:i'),
        ],
      ));

      $homeCheckup->update(['reminder_sent_at' => now()]);
    }

    $this->info("Sent {$homeCheckups->count()} home checkup reminder(s).");

    return self::SUCCESS;
  }
}
