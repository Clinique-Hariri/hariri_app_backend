<?php

namespace Modules\Ads\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Ads\Constants\UserType;
use Modules\Ads\Http\Resources\AdResource;
use Modules\Ads\Models\Ad;

class AdsApiController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $this->validateRequest($request, [
            'user_type' => 'nullable|in:' . implode(',', UserType::all()),
        ]);

        $query = Ad::active();

        if ($request->filled('user_type')) {
            $query->whereJsonContains('user_types', $request->user_type);
        }

        $ads = $query->get();

        return $this->successResponse(
            data: AdResource::collection($ads)
        );
    }
}
