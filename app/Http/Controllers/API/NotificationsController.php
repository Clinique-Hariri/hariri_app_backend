<?php

namespace App\Http\Controllers\API;

use App\Constants\NotificationMessages;
use App\Http\Requests\SendTestNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Exception;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;


class NotificationsController extends Controller
{
  use ApiResponseTrait;

  public function index(Request $request)
  {
    try {
      $user = auth()->user();
      $notifications = $user->notifications()->orderBy('created_at', 'desc');
      if (request()->boolean('paginate')) {
        $notifications = $notifications->paginate($request->get('per_page', 10));
      } else {
        $notifications = $notifications->get();
      }
      return $this->successResponse(
        data: NotificationResource::collection($notifications),
      );
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), 500);
    }
  }

  public function unread(Request $request)
  {
    try {
      $user = auth()->user();
      $unreadNotifications = $user->unreadNotifications()->orderBy('created_at', 'desc');

      if (request()->boolean('paginate')) {
        $unreadNotifications = $unreadNotifications->paginate($request->get('per_page', 10));
      } else {
        $unreadNotifications = $unreadNotifications->get();
      }

      return $this->successResponse(
        data: NotificationResource::collection($unreadNotifications),
      );
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), 500);
    }
  }

  public function markAsRead($id, Request $request)
  {
    try {
      $user = auth()->user();
      $notification = $user->notifications()->findOrFail($id);
      $notification->markAsRead();
      return $this->successResponse(
        message: 'Notification marked as read successfully.',
        data: new NotificationResource($notification)
      );
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), 500);
    }
  }

  public function sendTestNotification(SendTestNotificationRequest $request)
  {
    try {
      $validated = $request->validated();
      $user = User::findOrFail($validated['user_id']);

      $title = [
        'en' => NotificationMessages::title(NotificationMessages::TEST_NOTIFICATION, 'en') ?? 'Test Notification',
        'ar' => NotificationMessages::title(NotificationMessages::TEST_NOTIFICATION, 'ar') ?? 'اشعار تجريبي',
        'fr' => NotificationMessages::title(NotificationMessages::TEST_NOTIFICATION, 'fr') ?? 'Notification de test',
      ];

      $body = [
        'en' => NotificationMessages::body(NotificationMessages::TEST_NOTIFICATION, 'en') ?? 'This is a test notification.',
        'ar' => NotificationMessages::body(NotificationMessages::TEST_NOTIFICATION, 'ar') ?? 'هذا اشعار تجريبي.',
        'fr' => NotificationMessages::body(NotificationMessages::TEST_NOTIFICATION, 'fr') ?? 'Ceci est une notification de test.',
      ];

      $user->notify(new NewMessageNotification(
        key: NotificationMessages::TEST_NOTIFICATION,
        data: [
          'type' => NotificationMessages::TEST_NOTIFICATION,
          'sender_id' => auth()->id(),
        ],
        channels: ['database', 'fcm'],
        custom_title: $title,
        custom_body: $body,
      ));

      return $this->successResponse(
        message: 'Test notification sent successfully.',
        data: [
          'user_id' => $user->id,
          'channels' => ['database', 'fcm'],
        ]
      );
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage(), 500);
    }
  }
}
