<?php

namespace App\Http\Middleware;

use App\Models\IdempotencyKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifySystemApiKey
{
  /**
   * Handle an incoming request.
   *
   * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $key = $request->header('X-SYSTEM-KEY');
    if (!$key || $key !== config('system.shared_api_key')) {
      return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Idempotency Key check
    $idempotency = $request->header('X-IDEMPOTENCY-KEY');
    if (!$idempotency) {
      return response()->json(['message' => 'Missing Idempotency Key'], 400);
    }

    // If key exists → return stored response
    $existing = IdempotencyKey::where('key', $idempotency)->first();
    if ($existing && $existing->response) {
      return response()->json($existing->response, 200);
    }

    // If not exists → create placeholder and continue
    if (!$existing) {
      IdempotencyKey::create([
        'key' => $idempotency,
        'response' => null
      ]);
    }

    return $next($request);
  }
}
