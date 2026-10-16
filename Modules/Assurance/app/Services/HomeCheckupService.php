<?php

namespace Modules\Assurance\Services;

use App\Models\User;
use App\Support\Enum\PermissionNames;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Assurance\Constants\HomeCheckup\Status;
use Modules\Assurance\Constants\HomeCheckup\VisitType;
use Modules\Assurance\Models\HomeCheckup;
use Modules\Assurance\Models\HomeService;

class HomeCheckupService
{
  public function list(Request $request, User $user): LengthAwarePaginator|Collection
  {
    $query = HomeCheckup::query()
      ->with(['location', 'services']);

    // Admins see all requests; regular users only see their own.
    if (!$user->hasPermissionTo(PermissionNames::MANAGE_EXAMINATIONS)) {
      $query->where('user_id', $user->id);
    }

    if ($request->has('status')) {
      $query->where('status', $request->get('status'));
    }

    $query->latest();

    if ($request->boolean('paginate')) {
      return $query->paginate($request->get('per_page', 10));
    }

    return $query->limit($request->get('limit', 20))->get();
  }

  public function create(User $user, array $data): HomeCheckup
  {
    return DB::transaction(function () use ($user, $data) {
      $location = $user->locations()->create([
        'address' => $data['location']['address'],
        'longitude' => $data['location']['longitude'],
        'latitude' => $data['location']['latitude'],
        'status' => \Modules\Locations\Constants\Status::ACTIVE,
      ]);

      $homeServices = HomeService::query()
        ->whereIn('id', $data['service_ids'])
        ->get()
        ->keyBy('id');

      $basePrice = $this->resolveBasePrice($data['visit_type'] ?? null);
      $servicesTotal = $homeServices->sum('price');
      $totalPrice = $basePrice + $servicesTotal;

      $homeCheckup = HomeCheckup::create([
        'user_id' => $user->id,
        'location_id' => $location->id,
        'patient_number' => $data['patient_number'] ?? null,
        'notes' => $data['notes'] ?? null,
        'preferred_date' => $data['preferred_date'],
        'preferred_time' => $data['preferred_time'] ?? null,
        'visit_type' => $data['visit_type'] ?? null,
        'status' => Status::PENDING,
        'base_price' => $basePrice,
        'total_price' => $totalPrice,
      ]);

      foreach ($data['service_ids'] as $serviceId) {
        $service = $homeServices->get($serviceId);

        if (!$service) {
          continue;
        }

        $homeCheckup->services()->create([
          'home_service_id' => $service->id,
          'service_name' => $service->name,
          'service_price' => $service->price,
        ]);
      }

      return $homeCheckup->load(['location', 'services']);
    });
  }

  public function updateStatus(HomeCheckup $homeCheckup, User $actor, string $status, ?string $rejectionReason = null): HomeCheckup
  {
    $isAdmin = $actor->hasPermissionTo(PermissionNames::MANAGE_EXAMINATIONS);

    // Only admins may approve or reject.
    if (in_array($status, [Status::APPROVED, Status::REJECTED], true) && !$isAdmin) {
      abort(403, __('assurance::app.home_checkup.only_admin_can_review'));
    }

    // A user may only cancel their own pending request.
    if ($status === Status::CANCELLED) {
      if ($homeCheckup->user_id !== $actor->id) {
        abort(403, __('assurance::app.home_checkup.cannot_cancel_others'));
      }
      if ($homeCheckup->status !== Status::PENDING) {
        abort(422, __('assurance::app.home_checkup.only_pending_can_cancel'));
      }
    }

    $homeCheckup->update([
      'status' => $status,
      'rejection_reason' => $status === Status::REJECTED ? $rejectionReason : null,
      'reviewed_at' => in_array($status, [Status::APPROVED, Status::REJECTED], true) ? now() : $homeCheckup->reviewed_at,
    ]);

    return $homeCheckup->load(['location', 'services']);
  }

  public function delete(HomeCheckup $homeCheckup, User $actor): void
  {
    $isAdmin = $actor->hasPermissionTo(PermissionNames::MANAGE_EXAMINATIONS);

    if (!$isAdmin && $homeCheckup->user_id !== $actor->id) {
      abort(403, __('assurance::app.home_checkup.cannot_delete_others'));
    }

    $homeCheckup->delete();
  }

  private function resolveBasePrice(?string $visitType): float
  {
    if ($visitType === null) {
      return 0.00;
    }

    return VisitType::get_price($visitType);
  }
}
