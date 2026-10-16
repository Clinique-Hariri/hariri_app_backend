<?php

namespace Modules\Assurance\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Modules\Assurance\Constants\MedicalService\Status;
use Modules\Assurance\Models\HomeService;

class HomeServiceService
{
  public function list(Request $request): LengthAwarePaginator|Collection
  {
    $query = HomeService::query()
      ->where('status', Status::ACTIVE)
      ->latest();

    if ($request->has('search')) {
      $search = $request->get('search');
      $query->where(function ($q) use ($search) {
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('description', 'like', "%{$search}%");
      });
    }

    if ($request->boolean('paginate')) {
      return $query->paginate($request->get('per_page', 10));
    }

    return $query->limit($request->get('limit', 20))->get();
  }
}
