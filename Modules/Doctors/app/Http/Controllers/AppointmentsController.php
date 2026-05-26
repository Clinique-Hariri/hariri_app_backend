<?php

namespace Modules\Doctors\Http\Controllers;

use App\Constants\NotificationMessages;
use App\Constants\Statuses\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use App\Support\Enum\PermissionNames;
use App\Support\Enum\UserTypes;
use Illuminate\Http\Request;
use Modules\Doctors\Constants\Appointment\Status;
use Modules\Doctors\Datatables\AppointmentDatatable;
use Modules\Doctors\Http\Requests\CreateAppointmentRequest;
use Modules\Doctors\Models\Appointment;

class AppointmentsController extends Controller
{
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_APPOINTMENTS)) {
      return redirect()->route('unauthorized');
    }

    $datatable = new AppointmentDatatable();
    if ($request->wantsJson()) {
      return $datatable->datatables($request);
    }
    return view("doctors::appointment.index")->with([
      "columns" => $datatable::columns(),
    ]);
  }

  public function create()
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_APPOINTMENTS)) {
      return redirect()->route('unauthorized');
    }
    return view("doctors::appointment.create-edit")->with(["edit" => false]);
  }

  public function store(CreateAppointmentRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_APPOINTMENTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $data = $request->validated();
      $data['user_id'] = auth()->id();
      $data['status'] = Status::CONFIRMED;

      Appointment::create($data);

      return redirect()->route('appointments.index')->with('success', __('app.created_successfully', ['name' => __('doctors::app.appointment')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function update(Request $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_APPOINTMENTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $validated = $request->validate([
        'status' => 'required|in:' . implode(',', Status::all()),
        'time' => 'nullable|date_format:H:i',
      ]);

      $model = Appointment::with(['user'])->findOrFail($id);
      if (
        ($model->status == Status::PENDING)
        || ($model->status == Status::CONFIRMED && $validated['status'] == Status::COMPLETED)
      ) {

        $model->update($validated);

        $notification_key = match ($validated['status']) {
          Status::CONFIRMED => NotificationMessages::APPOINTMENT_CONFIRMATION,
          Status::CANCELLED => NotificationMessages::APPOINTMENT_CANCELLATION,
          Status::COMPLETED => NotificationMessages::APPOINTMENT_COMPLETION,
        };

        $model->user->notify(new NewMessageNotification(
          key: $notification_key,
          data: [
            'type' => 'appointments',
            'date' => $model->date?->format('Y-m-d'),
            'time' => $model->time?->format('H:i'),
            'image' => $model->doctor?->avatar_url??null,
          ],
          channels: ['database', 'fcm'],
          replace: ['date' => $model->date?->format('Y-m-d'), 'time' => $model->time?->format('H:i')],
        ));

      } else {
        return redirect()->route('appointments.index')->with('error', __('doctors::app.invalid_status_change', ['status' => Status::get_name($model->status)]));
      }
      return redirect()->route('appointments.index')->with('success', __('app.updated_successfully', ['name' => __('doctors::app.appointment')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function destroy($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_APPOINTMENTS)) {
      return redirect()->route('unauthorized');
    }
    try {
      $model = Appointment::findOrFail($id);
      $model->delete();
      return redirect()->route('appointments.index')->with('success', __('app.deleted_successfully', ['name' => __('doctors::app.appointment')]));

    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
