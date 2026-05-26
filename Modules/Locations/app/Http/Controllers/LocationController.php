<?php

namespace Modules\Locations\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Modules\Locations\Constants\Status;
use Modules\Locations\Resources\LocationResource;

class LocationController extends Controller
{
  use ApiResponseTrait;

  public function index(Request $request)
  {
    try {
      $locations = auth()->user()->locations()->where('status', Status::ACTIVE);

      if($request->has('search')){
        $locations = $locations->whereRaw("LOWER(address) LIKE ?", ['%' . strtolower($request->search) . '%']);
      }

      return $this->successResponse(
        data: LocationResource::collection($locations->paginate(10))
      );
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }

  public function create(Request $request)
  {
    try {
      $this->validateRequest($request, [
        'address' => 'required|string|max:255',
        'longitude' => 'required|numeric',
        'latitude' => 'required|numeric',
      ]);

      $location = auth()->user()->locations()->create($request->all());

      return $this->successResponse(
        data: new LocationResource($location)
      );
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }

  public function update(Request $request)
  {
    try {
      $this->validateRequest($request, [
        'id' => 'required|exists:locations,id',
        'address' => 'required|string|max:255',
        'longitude' => 'required|numeric',
        'latitude' => 'required|numeric',
      ]);

      $location = auth()->user()->locations()
        ->where('id', $request->id)
        ->firstOrFail();


      $location->update($request->all());

      return $this->successResponse(
        data: new LocationResource($location)
      );
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }

  public function delete(Request $request)
  {
    $this->validateRequest($request, [
      'id' => 'required|exists:locations,id',
    ]);

    try {
      $location = auth()->user()->locations()
        ->where('id', $request->id)
        ->firstOrFail();

      $location->delete();

      return $this->successResponse();
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }
}
