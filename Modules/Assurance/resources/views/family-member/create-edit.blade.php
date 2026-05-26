@extends('layouts/contentNavbarLayout')

@section('title', __('assurance::app.user'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span class="text-muted fw-light">{{ __($edit ? 'app.edit' : 'app.add') }} / {{$edit? $user->fullname.'/':''}}</span> @lang('assurance::app.family-member')
    </div>
    <div class="col-md-auto">
      @permission(\App\Support\Enum\PermissionNames::MANAGE_PATIENTS)
      <a href="{{ route('patients.family-members.index', $user->id) }}" class="text-white text-decoration-none">
        <button type="button" class="btn btn-primary" style="float: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
          <span class="tf-icons bx bx-arrow-back"></span> @lang('app.back')
        </button>
      </a>
      @endpermission
    </div>
  </h4>

  @if ($edit)
    <form action="{{ route('patients.family-members.update', [$user->id, $model->id]) }}" method="POST"
          enctype="multipart/form-data">
      @csrf
      @method('PATCH')
      @else
        <form action="{{ route('patients.family-members.store', $user->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @endif

          <div class="row">
            <div class="col-xl">
              <div class="card mb-4">
                <div class="row card-body">
                  <div class="mb-3 col-md-6">
                    <label for="firstname" class="form-label">@lang('assurance::app.firstname')</label>
                    <input type="text" name="firstname" class="form-control" id="firstname"
                           placeholder="@lang('assurance::app.firstname')"
                           value="{{ old('firstname', $model->firstname ?? '') }}" required>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label for="lastname" class="form-label">@lang('assurance::app.lastname')</label>
                    <input type="text" name="lastname" class="form-control" id="lastname"
                           placeholder="@lang('assurance::app.lastname')"
                           value="{{ old('lastname', $model->lastname ?? '') }}"
                           required>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="phone" class="form-label">@lang('assurance::app.phone')</label>
                    <input type="text" name="phone" class="form-control phone-mask" id="phone"
                           placeholder="@lang('assurance::app.phone')" value="{{ old('phone', $model->phone ?? '') }}"
                           required>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="gender" class="form-label">@lang('assurance::app.gender')</label>
                    <select name="gender" class="form-select" id="gender" required>
                      <option value="">@lang('app.select_option')</option>
                      @foreach(\App\Constants\Gender::all(true) as $key => $value)
                        <option
                          value="{{ $key }}" {{ old('gender', $model->gender ?? '') == $key ? 'selected' : '' }}>{{ $value }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="blood_type" class="form-label">@lang('assurance::app.blood_type')</label>
                    <select name="blood_type" class="form-select" id="blood_type">
                      <option value="">@lang('app.select_option')</option>
                      @foreach(\App\Constants\BloodType::all(true) as $key => $value)
                        <option
                          value="{{ $key }}" {{ old('blood_type', $model->blood_type ?? '') == $key ? 'selected' : '' }}>{{ $value }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="birthdate" class="form-label">@lang('assurance::app.birthdate')</label>
                    <input type="date" name="birthdate" class="form-control date-mask" id="birthdate"
                           placeholder="@lang('assurance::app.birthdate')"
                           value="{{ old('birthdate', $edit && $model->birthdate ? $model?->birthdate->format('Y-m-d') : '') }}">
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="family_relation" class="form-label">{{ __('assurance::app.family_relation') }}</label>
                    <input type="text" name="family_relation" class="form-control" id="family_relation"
                           placeholder="{{ __('assurance::app.family_relation') }}"
                           value="{{ old('family_relation', $model?->family_relation ?? '') }}">
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="status" class="form-label">{{ __('assurance::app.status') }}</label>
                    <select name="status" class="form-select" id="status" required>
                      <option value="">{{ __('app.select_option') }}</option>
                      @foreach(\Modules\Assurance\Constants\FamilyMember\Status::all(true) as $key => $value)
                        <option
                          value="{{ $key }}" {{ old('status', $user->status ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="avatar" class="form-label">{{ __('assurance::app.avatar') }}</label>
                    <div class="input-group">
                      <input type="file" name="avatar" class="form-control" id="avatar"
                             placeholder="{{ __('assurance::app.avatar') }}">
                    </div>
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
