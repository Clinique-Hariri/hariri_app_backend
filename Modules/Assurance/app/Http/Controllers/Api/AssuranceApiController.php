<?php

namespace Modules\Assurance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Assurance\Constants\FamilyMember\Status;
use Modules\Assurance\Http\Resources\FamilyMemberResource;
use Modules\Assurance\Http\Resources\MedicalExaminationResource;
use Modules\Assurance\Models\FamilyMember;
use Modules\Assurance\Models\MedicalExamination;
use Modules\Transactions\Constants\Type;
use Modules\Transactions\Http\Resources\TransactionResource;
use Modules\Transactions\Models\Transaction;

class AssuranceApiController extends Controller
{
  use ApiResponseTrait;
  public function getFamilyMembers(Request $request)
  {
    if (auth()->user()->is_insured){
      $model = FamilyMember::where('user_id', auth()->id())
      ->where('status', Status::ACTIVE);

      if (request()->boolean('paginate')) {
        $model = $model->paginate($request->get('per_page', 10));
      } else {
        $model = $model->get();
      }

      return $this->successResponse(
        data: FamilyMemberResource::collection($model),
      );
    } else{
      return $this->errorResponse(
        message: "user is not insured"
      );
    }
  }

  public function getAccountStatement(Request $request)
  {
    if (auth()->user()->is_insured){
      $model = User::findOrFail(auth()->id());
      $transaction = $model->transactions()
        ->where('type', Type::CREDIT)
        ->where('status', \Modules\Transactions\Constants\Status::COMPLETED)
        ->orderBy('updated_at', 'desc')
        ->first();

      return $this->successResponse(
        data: [
          'account_owner' => $model->fullname,
          'wallet_balance' => $model->wallet_balance,
          'last_recharge_amount' => $transaction?->amount??0.00,
          'last_recharge_date' => $transaction?->updated_at?->format('Y-m-d')??null
        ]
      );
    } else{
      return $this->errorResponse(
        message: "user is not insured"
      );
    }
  }

  public function getExaminations(Request $request)
  {
    $data = $request->validate([
      'family_member_id' => ['required', 'exists:family_members,id']
    ]);

    if (auth()->user()->is_insured){
      $model = MedicalExamination::with(['familyMember', 'examinationServices'])
        ->where('family_member_id', $data['family_member_id'])
        ->whereHas('familyMember', function ($query){
          $query->where('user_id', auth()->id());
        })
      ->orderBy('medical_examinations.date')
      ->orderBy('medical_examinations.id')
      ->get();

      return $this->successResponse(
        data: MedicalExaminationResource::collection($model)
      );
    } else{
      return $this->errorResponse(
        message: "user is not insured"
      );
    }
  }
  public function getTransactions(Request $request)
  {
    if (auth()->user()->is_insured){
      $user = User::findOrFail(auth()->id());
      $model = $user->transactions()
        ->where('status', \Modules\Transactions\Constants\Status::COMPLETED)
        ->orderBy('updated_at', 'desc')
        ->get();

      return $this->successResponse(
        data: TransactionResource::collection($model)
      );
    } else{
      return $this->errorResponse(
        message: "user is not insured"
      );
    }
  }
}
