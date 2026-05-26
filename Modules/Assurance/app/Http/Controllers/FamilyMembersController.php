<?php

namespace Modules\Assurance\Http\Controllers;

use App\Constants\NotificationMessages;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use App\Support\Enum\PermissionNames;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Modules\Assurance\Constants\FamilyMember\Status;
use Modules\Assurance\Datatables\FamilyMemberDatatable;
use Modules\Assurance\Http\Requests\CreateFamilyMemberRequest;
use Modules\Assurance\Http\Requests\EditFamilyMemberRequest;
use Modules\Assurance\Models\FamilyMember;

class FamilyMembersController extends Controller
{
  public function index($userId, Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    $user = User::findOrFail($userId);
    $datatable = new FamilyMemberDatatable();
    if ($request->wantsJson()) {
      return $datatable->datatables($request, $user->id);
    }
    return view("assurance::family-member.index")->with([
      "columns" => $datatable::columns(),
      "user" => $user,
    ]);
  }
  public function create($userId)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }
    $user = User::findOrFail($userId);
    return view("assurance::family-member.create-edit")->with(["edit" => false,
      "user" => $user
    ]);
  }

public function edit($userId, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }
    $user = User::findOrFail($userId);
    $familyMember = $user->familyMembers()->findOrFail($id);
    return view("assurance::family-member.create-edit")->with([
      "edit" => true,
      "user" => $user,
      "model" => $familyMember,
    ]);
  }

  public function store($userId, CreateFamilyMemberRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $data = $request->validated();

      if ($request->hasFile('avatar')) {
        $data['avatar'] = storeWebP($request->file('avatar'), 'uploads/users/family-members/avatars');
      } else {
        $file = new File(public_path('assets/img/avatars/1.png'));
        $data['avatar'] = storeWebP($file, 'uploads/users/family-members/avatars');
      }

      $data['insurance_number'] = FamilyMember::generateUniqueInsuranceNumber($userId);
      $model = User::findOrFail($userId)->familyMembers()->create($data);

      $model->user->notify(new NewMessageNotification(
        key: NotificationMessages::FAMILY_MEMBER_ADDED,
        data: [
          'image' => $model->doctor?->avatar_url??null,
        ],
        channels: ['database', 'fcm'],
        replace: ['family_member_name' => $model->fullname]
      ));
      return redirect()->route('patients.family-members.index', $userId)->with('success', __('app.created_successfully', ['name' => __('assurance::app.family-member')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function update($userId, $id, EditFamilyMemberRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $data = $request->validated();
      $familyMember = FamilyMember::where('user_id', $userId)->findOrFail($id);

      if ($request->hasFile('avatar')) {
        $data['avatar'] = storeWebP($request->file('avatar'), 'uploads/users/family-members/avatars');
      }

      $familyMember->update($data);

      return redirect()->route('patients.family-members.index', $userId)->with('success', __('app.updated_successfully', ['name' => __('assurance::app.family-member')]));
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
      $familyMember = FamilyMember::where('user_id', $userId)->findOrFail($id);
      $familyMember->delete();

      return redirect()->route('patients.family-members.index', $userId)->with('success', __('app.deleted_successfully', ['name' => __('assurance::app.family-member')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function getFamilyMemberWithQrCode($qrCode)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $familyMember = FamilyMember::with(['user'])
        ->Where('insurance_number', $qrCode)
        ->where('status', Status::ACTIVE)
        ->first();
      if (!$familyMember) {
        return response()->json(['error' => __('app.not_found')], 404);
      }
      return response()->json($familyMember);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  public function generateInsuranceCard(Request $request, $userId, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    try {

      $family_member = FamilyMember::with(['user'])
        ->where('user_id', $userId)
        ->findOrFail($id);

      return view('assurance::family-member.insurance-card', [
        'family_member' => $family_member,
      ]);


    } catch (\Throwable $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }}
