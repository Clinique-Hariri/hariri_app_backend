<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FcmService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FcmController extends Controller
{

  public function getAccessToken(Request $request)
  {
      $fcmService = new FcmService();
      return response()->json([
        'status' => 'success',
        'access-token' => $fcmService->getAccessToken()
      ]);
  }
    public function sendToDevice(Request $request)
    {
        $fcmService = new FcmService();
        $token = $request->token;
        return $fcmService->sendToDevice(
            $token,
            'Hello World',
            'This is a test notification',
        );
    }

    public function sendToMultiple(Request $request)
    {
        $fcmService = new FcmService();
        $tokens = $request->tokens;
        return $fcmService->sendToMultipleDevices(
            $tokens,
            'Bulk Notification',
            'This goes to multiple devices'
        );
    }

    public function sendToUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'body' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'data' => ['sometimes', 'array'],
        ]);

        $user = User::findOrFail($validated['user_id']);
        $tokens = $user->deviceTokens()->pluck('token');

        if ($tokens->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'User has no registered device tokens.',
            ], 404);
        }

        $fcmService = new FcmService();

        $result = $fcmService->sendToMultipleDevices(
            $tokens->all(),
            $validated['title'] ?? 'Hello World',
            $validated['body'] ?? 'This is a test notification',
            $validated['data'] ?? ['key' => 'value'],
        );

        return response()->json([
            'status' => 'success',
            'user_id' => $user->id,
            'tokens_count' => $tokens->count(),
            'results' => $result['results'],
        ]);
    }

    public function sendToTopic(Request $request)
    {
        $fcmService = new FcmService();
        $topic = $request->topic;
        return $fcmService->sendToTopic(
            $topic,
            'Breaking News',
            'Something important happened'
        );
    }

    public function sendToCondition(Request $request)
    {
        $fcmService = new FcmService();
        $condition = $request->condition;
        return $fcmService->sendToCondition(
            $condition,
            'Sports News',
            'Your favorite team won!'
        );
    }

    public function sendDataMessage(Request $request)
    {
        $fcmService = new FcmService();
        $token = $request->token;
        return $fcmService->sendDataMessage(
            $token,
            ['action' => 'sync', 'timestamp' => time()]
        );
    }

    public function sendCustomMessage(Request $request)
    {
        $fcmService = new FcmService();
        $token = $request->token;
        $customMessage = [
            'token' => $token,
            'notification' => [
                'title' => 'Custom Title',
                'body' => 'Custom Body',
                'image' => 'https://example.com/image.jpg'
            ],
            'data' => [
                'custom_key' => 'custom_value'
            ],
            'android' => [
                'priority' => 'high',
                'ttl' => '3600s',
                'notification' => [
                    'icon' => 'stock_ticker_update',
                    'color' => '#f45342',
                    'sound' => 'default',
                    'channel_id' => 'high_priority'
                ]
            ],
            'apns' => [
                'headers' => [
                    'apns-priority' => '10'
                ],
                'payload' => [
                    'aps' => [
                        'alert' => [
                            'title' => 'Custom iOS Title',
                            'body' => 'Custom iOS Body'
                        ],
                        'badge' => 42,
                        'sound' => 'default'
                    ]
                ]
            ]
        ];

        return $fcmService->sendCustomMessage($customMessage);
    }
}
