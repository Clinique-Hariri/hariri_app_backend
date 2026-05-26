<?php

namespace Modules\Assurance\Http\Controllers;

use App\Constants\Gender;
use App\Constants\NotificationMessages;
use App\Datatables\UserDatatable;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use App\Support\Enum\PermissionNames;
use App\Support\Enum\UserRoles;
use App\Support\Enum\UserTypes;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Assurance\Datatables\PatientDatatable;
use Modules\Assurance\Http\Requests\ActivateInsuranceRequest;
use Modules\Assurance\Models\FamilyMember;
use Modules\Transactions\Constants\Status;
use Modules\Transactions\Constants\Type;
use Mpdf\Mpdf;

class PatientsController extends Controller
{

  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    $patients = new PatientDatatable();
    if ($request->wantsJson()) {
      return $patients->datatables($request);
    }
    return view("assurance::patient.index")->with([
      "columns" => $patients::columns(),
    ]);
  }

  public function show(Request $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $user = User::with([
        'familyMembers',
        'transactions' => function ($q) {
          $q->latest()->take(10);
        }
      ])->findOrFail($id);

      if ($user->type !== UserTypes::USER) {
        return redirect()->back()->with('error', __('app.not_a_patient'));
      }

      return view('assurance::patient.show')->with([
        'user' => $user,
      ]);

    } catch (\Throwable $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function addFunds(Request $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $user = User::findOrFail($id);

      if (!$user->is_insured) {
        return redirect()->back()->with('error', __('app.patient_is_not_insured'));
      }

      return view('assurance::patient.add-funds')->with([
        'user' => $user,
      ]);

    } catch (\Throwable $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function addFundsAction(Request $request, $id)
  {
    $validated = $request->validate([
      'amount' => ['required', 'numeric', 'min:0']
    ]);

    try {
      DB::beginTransaction();
      $user = User::findOrFail($id);

      if (!$user->is_insured) {
        return redirect()->back()->with('error', __('app.patient_is_not_insured'));
      }

      $user->increment('wallet_balance', $validated['amount']);

      $transaction = $user->transactions()->create([
        'amount' => $validated['amount'],
        'type' => Type::CREDIT,
        'status' => Status::COMPLETED,
        'user_id' => auth()->id()
      ]);

      DB::commit();

      $user->notify(new NewMessageNotification(
        key: NotificationMessages::WALLET_CHARGING,
        data: ['type' => NotificationMessages::WALLET_CHARGING, 'amount' => $validated['amount']],
        channels: ['database', 'fcm'],
        replace: ['amount' => $validated['amount']]
      ));

      return redirect()->route('patients.index')->with([
        'success'=> __('assurance::app.amount_added_successfully'),
        'open_url' => route('patients.generate-payment-receipt', [
          'userId' => $user->id,
          'amount' => $transaction->amount,
          'receipt_number' => $transaction->transaction_number,
        ]),
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function activateInsurance(Request $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $user = User::findOrFail($id);

      if ($user->is_insured) {
        return redirect()->back()->with('error', __('app.patient_already_insured'));
      }
      return view('assurance::patient.activate-insurance')->with([
        'user' => $user,
      ]);
    } catch (\Throwable $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function activateInsuranceAction(ActivateInsuranceRequest $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    try {

      $data = $request->validated();
      $data['insurance_number'] = User::generateUniqueInsuranceNumber();
      $data['wallet_balance'] = $data['wallet_balance'] ?? 0;
      $data['is_insured'] = true;
      $data['insurance_date'] = now();

      $user = User::findOrFail($id);

      if ($request->hasFile('contract')) {
        storeWebPWithSpatie($user, $request->file('contract'), User::CONTRACT);
      }

      if ($user->is_insured) {
        return redirect()->back()->with('error', __('app.patient_already_insured'));
      }

      DB::beginTransaction();

      //update user data
      $user->update($data);

      //crete family members
      $user->familyMembers()->create([
        'firstname' => $user->firstname,
        'lastname ' => $user->lastname,
        'birthdate' => $user->birthdate,
        'phone' => $user->phone,
        'gender' => $user->gender,
        'blood_type' => $user->blood_type,
        'avatar' => $user->avatar,
        'insurance_number' => $data['insurance_number'],
        'family_relation' => 'self',
      ]);

      if (isset($data['family_members']) && is_array($data['family_members'])) {
        foreach ($data['family_members'] as $member) {
          $member['gender'] = $member['gender'] ?? Gender::MALE;
          if (isset($member['avatar']) && $member['avatar']->isValid()) {
            $member['avatar'] = storeWebP($member['avatar'], 'uploads/users/family-members/avatars');
          } else {
            unset($member['avatar']);
          }
          $member['insurance_number'] = FamilyMember::generateUniqueInsuranceNumber();
          $user->familyMembers()->create($member);
        }
      }

      DB::commit();

      return redirect()->route('patients.index')->with('success', __('assurance::app.activate-insurance-success', ['name' => $user->fullname]));
    } catch (\Throwable $e) {
      DB::rollBack();
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function generateInsuranceContract(Request $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $user = User::findOrFail($id);

      $html = view('assurance::patient.contract', [
        'client_name' => $request->input('firstname', $user->firstname) . ' ' . $request->input('lastname', $user->lastname) ?? '_______________',
        'passport_number' => $request->input('passport_number', $user->passport_number) ?? '_______________',
        'birth_date' => $request->input('birthdate', $user->birthdate ? Carbon::parse($user->birthdate)->format('d/m/Y') : '_______________'),
        'client_address' => $request->input('full_address', $user->full_address) ?? '_______________',
        'client_phone' => $request->input('phone', $user->phone) ?? '_______________',
        'account_fee' => ($request->input('wallet_balance') ?? '_______________') . ' FCFA',
        'date' => now()->format('d/m/Y'),
      ])->render();

      $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'default_font' => 'dejavusans',
      ]);

      $mpdf->WriteHTML($html);
      return response($mpdf->Output('', 'S'), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="contrat_assurance.pdf"');

    } catch (\Throwable $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function generatePaymentReceipt(Request $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $user = User::findOrFail($id);

      $html = view('assurance::patient.payment-receipt', [
        'receipt_number' => $request->input('receipt_number', '_________________'),
        'date' => now()->format('d/m/Y'),
        'patient_name' => $user->fullname,
        'account_number' => $user->insurance_number ?? '_______________',
        'payer_name' => $request->input('received_from', $user->fullname) ?? '_______________',
        'amount' => $request->input('amount', '_______________'),
      ])->render();

      $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'default_font' => 'dejavusans',
      ]);

      $mpdf->WriteHTML($html);
      return response($mpdf->Output('', 'S'), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="receipt.pdf"');

    } catch (\Throwable $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function generateInsuranceCard(Request $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $user = User::findOrFail($id);

      return view('assurance::patient.insurance-card', [
        'user' => $user,
      ]);


    } catch (\Throwable $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
