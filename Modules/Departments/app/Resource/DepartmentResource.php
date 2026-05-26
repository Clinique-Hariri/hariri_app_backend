<?php

namespace Modules\Departments\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Departments\Models\Department;

/** @mixin Department */
class DepartmentResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'image' => $this->getFirstMediaUrl(Department::IMAGE),
      'description' => $this->description,
      'status' => $this->status,
      'doctors_count' => $this->doctors()->count(),
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];
  }
}
