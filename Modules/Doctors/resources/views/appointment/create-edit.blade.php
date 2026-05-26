@extends('layouts/contentNavbarLayout')

@section('title', __('doctors::app.appointment'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span class="text-muted fw-light">@lang('doctors::app.appointments')/</span>
      {{$edit ? __('app.edit') . ' '. $model->name : __('app.add') .' ' . __('doctors::app.appointment')}}
    </div>
    <div class="col-md-auto">
      @permission(\App\Support\Enum\PermissionNames::MANAGE_APPOINTMENTS)
      <a href="{{ route('appointments.index') }}" class="text-white text-decoration-none">
        <button type="button" class="btn btn-primary" style="float: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
          <span class="tf-icons bx bx-arrow-back"></span> @lang('app.back')
        </button>
      </a>
      @endpermission
    </div>
  </h4>

  @if ($edit)
    <form action="{{ route('appointments.update', $model->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PATCH')
      @else
        <form action="{{ route('appointments.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @endif

          <div class="row">
            <div class="col-xl">
              <div class="card mb-4">
                <div class="row card-body">
                  <div class="mb-3 col-md-6">
                    <label for="status" class="form-label">
                      @lang('doctors::app.doctor')
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <select name="doctor_id" class="form-select" id="doctor_id" required>
                      <option value="">{{ __('app.select_option') }}</option>
                      @foreach(\Modules\Doctors\Models\Doctor::where('status', 'active')->get()->pluck('fullname', 'id') as $key => $value)
                        <option
                          value="{{ $key }}" {{ old('doctor_id', $model->doctor_id ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                      @endforeach
                    </select>
                  </div>
{{--                  <div class="mb-3 col-md-6">--}}
{{--                    <label for="status" class="form-label">--}}
{{--                      @lang('user.user')--}}
{{--                      <span class="text-danger fs-6 ms-1">*</span>--}}
{{--                    </label>--}}
{{--                    <select name="user_id" class="form-select" id="user_id" required>--}}
{{--                      <option value="">{{ __('app.select_option') }}</option>--}}
{{--                      @foreach(\App\Models\User::where('type', \App\Support\Enum\UserTypes::USER)--}}
{{--                        ->where('status', 'active')->get()->pluck('fullname', 'id') as $key => $value)--}}
{{--                        <option--}}
{{--                          value="{{ $key }}" {{ old('user_id', $model->user_id ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>--}}
{{--                      @endforeach--}}
{{--                    </select>--}}
{{--                  </div>--}}
                  <div class="mb-3 col-md-6">
                    <label for="patient_name" class="form-label">
                      @lang('doctors::app.patient_name')
                    </label>
                    <input type="text" class="form-control" id="patient_name" name="patient_name" placeholder="@lang('doctors::app.patient_name')"
                      value="{{ old('patient_name', $model->patient_name ?? '') }}" required>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="patient_phone" class="form-label">
                      @lang('doctors::app.patient_phone')
                    </label>
                    <input type="text" class="form-control" id="patient_phone" name="patient_phone" placeholder="@lang('doctors::app.patient_phone')"
                      value="{{ old('patient_phone', $model->patient_phone ?? '') }}" required>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="patient_age" class="form-label">
                      @lang('doctors::app.patient_age')
                    </label>
                    <input type="number" class="form-control" id="patient_age" name="patient_age" placeholder="@lang('doctors::app.patient_age')"
                      value="{{ old('patient_age', $model->patient_age ?? '') }}" min="0" max="120" required>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="date" class="form-label">
                      @lang('doctors::app.date')
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <input type="date" class="form-control" id="date" name="date"
                           value="{{ old('date', $model->date ?? '') }}" required>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="time" class="form-label">
                      @lang('doctors::app.time')
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <input type="time" class="form-control" id="time" name="time"
                           value="{{ old('time', $model->time ?? '') }}" required>
                  </div>

                  <div class="form-group" style="text-align: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
                    <button type="submit" class="btn btn-primary">{{ __('app.send') }}</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
        @endsection
