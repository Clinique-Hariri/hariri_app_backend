<?php

namespace Modules\Assurance\Http\Controllers;

use App\Constants\NotificationMessages;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use App\Support\Enum\PermissionNames;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Assurance\Datatables\ExaminationDatatable;
use Modules\Assurance\Datatables\FamilyMemberDatatable;
use Modules\Assurance\Http\Requests\CreateExaminationRequest;
use Modules\Assurance\Http\Requests\CreateFamilyMemberRequest;
use Modules\Assurance\Http\Requests\EditFamilyMemberRequest;
use Modules\Assurance\Models\ExaminationService;
use Modules\Assurance\Models\FamilyMember;
use Modules\Assurance\Models\MedicalExamination;
use Modules\Transactions\Constants\Status;
use Modules\Transactions\Constants\Type;

class ExaminationsController extends Controller
{
  public function index($userId, Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    $user = User::findOrFail($userId);
    $datatable = new ExaminationDatatable();
    if ($request->wantsJson()) {
      return $datatable->datatables($request, $user->id);
    }
    return view("assurance::examination.index")->with([
      "columns" => $datatable::columns(),
      "user" => $user,
    ]);
  }
  public function create($userId, Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }
    $data = $request->validate([
      'family_member_id' => ['sometimes', 'exists:family_members,id'],
    ]);

    $user = User::findOrFail($userId);
    return view("assurance::examination.create-edit")->with(["edit" => false,
      "user" => $user,
      'family_member_id' => $data['family_member_id'] ?? null,
    ]);
  }

  public function store($userId, CreateExaminationRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $data = $request->validated();
      $user = User::findOrFail($userId);

      if ($user->wallet_balance < $data['total_price']) {
        return redirect()->back()->with('error', __('assurance::app.insufficient_funds'));
      }
      DB::beginTransaction();
      $examination = MedicalExamination::create([
        'family_member_id' => $data['family_member_id'],
        'total_price' => $data['total_price'],
        'date' => $data['date'],
        'is_paid' => true,
      ]);

      foreach ($data['services'] as $service) {
        $model = $examination->examinationServices()->create([
          'medical_service_id' => $service['service_id']?? null,
          'service_name' => $service['service_name'],
          'service_price' => $service['service_price'],
        ]);

        if (isset($service['image']) && $service['image']) {
          storeWebPWithSpatie($model, $service['image'], ExaminationService::IMAGE);
        }

      }

      $user->decrement('wallet_balance', $data['total_price']);
      if ($data['total_price'] > 0) {
        $user->transactions()->create([
          'type' => Type::DEBIT,
          'status' => Status::COMPLETED,
          'amount' => $data['total_price'],
          'details' => 'Wallet deduction for examination #'. $examination->id .' for family member ' . $user->familyMembers()->find($data['family_member_id'])->fullname,
        ]);

        $user->notify(new NewMessageNotification(
          key: NotificationMessages::WALLET_DEDUCTION,
          data: ['type' => NotificationMessages::WALLET_DEDUCTION, 'amount' => $data['total_price']],
          channels: ['database', 'fcm'],
          replace: ['amount' => $data['total_price']]
        ));
      }
      DB::commit();

      $user->notify(new NewMessageNotification(
        key: NotificationMessages::MEDICAL_VISIT_ADDED,
        data: [],
        channels: ['database', 'fcm'],
        replace: [
          'family_member_name' => $user->familyMembers()->find($data['family_member_id'])->fullname,
          'date' => $data['date'],
        ]
      ));

      return redirect()->route('patients.examinations.index', $userId)->with('success', __('app.created_successfully', ['name' => __('assurance::app.examination')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function destroy($userId, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $examination = MedicalExamination::whereHas('familyMember', function ($query) use ($userId) {
        $query->where('user_id', $userId);
      })->findOrFail($id);
      $examination->examinationServices()->each(function ($service) {
        $service->clearMediaCollection(ExaminationService::IMAGE);
        $service->delete();
      });
      $examination->delete();
      return redirect()->route('patients.examinations.index', $userId)->with('success', __('app.deleted_successfully', ['name' => __('assurance::app.examination')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
