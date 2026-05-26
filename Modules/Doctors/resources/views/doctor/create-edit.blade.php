@extends('layouts/contentNavbarLayout')

@section('title', __('doctors::app.doctor'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span class="text-muted fw-light">@lang('doctors::app.doctors')/</span>
      {{$edit ? __('app.edit') . ' '. $model->name : __('app.add') .' ' . __('doctors::app.doctor')}}
    </div>
    <div class="col-md-auto">
      @permission(\App\Support\Enum\PermissionNames::MANAGE_DOCTORS)
      <a href="{{ route('doctors.index') }}" class="text-white text-decoration-none">
        <button type="button" class="btn btn-primary" style="float: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
          <span class="tf-icons bx bx-arrow-back"></span> @lang('app.back')
        </button>
      </a>
      @endpermission
    </div>
  </h4>

  @if ($edit)
    <form action="{{ route('doctors.update', $model->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PATCH')
      @else
        <form action="{{ route('doctors.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @endif

          <div class="row">
            <div class="col-xl">
              <div class="card mb-4">
                <div class="row card-body">
                  <div class="mb-3 col-md-6">
                    <label for="firstname" class="form-label">
                      @lang('doctors::app.firstname')
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="@lang('doctors::app.firstname')"
                      value="{{ old('firstname', $model->firstname ?? '') }}" required>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label for="lastname" class="form-label">
                      @lang('doctors::app.lastname')
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="@lang('doctors::app.lastname')"
                      value="{{ old('lastname', $model->lastname ?? '') }}" required>
                  </div>

                  <div class="mb-3 col-md-4">
                    <label for="display_label" class="form-label">
                      @lang('doctors::app.display_label')
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <select name="display_label" class="form-select" id="speciality_id" required>
                      <option value="">{{ __('app.select_option') }}</option>
                      <option value="department" {{ old('display_label', $model->display_label ?? '') == 'department' ? 'selected' : '' }}>@lang('doctors::app.department')</option>
                      <option value="speciality" {{ old('display_label', $model->display_label ?? '') == 'speciality' ? 'selected' : '' }}>@lang('doctors::app.speciality')</option>
                    </select>
                  </div>

                  <div class="mb-3 col-md-4">
                    <label for="speciality_id" class="form-label">
                      @lang('doctors::app.department')
                    </label>
                    <select name="department_id" class="form-select" id="department_id">
                      <option value="">{{ __('app.select_option') }}</option>
                      @foreach(\Modules\Departments\Models\Department::pluck('name', 'id') as $key => $value)
                        <option
                          value="{{ $key }}" {{ old('department_id', $model->department_id ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="mb-3 col-md-4">
                    <label for="speciality_id" class="form-label">
                      @lang('doctors::app.speciality')
                    </label>
                    <select name="speciality_id" class="form-select" id="speciality_id">
                      <option value="">{{ __('app.select_option') }}</option>
                      @foreach(\Modules\Doctors\Models\Speciality::pluck('name', 'id') as $key => $value)
                        <option
                          value="{{ $key }}" {{ old('speciality_id', $model->speciality_id ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="mb-3 col-md-4">
                    <label for="sort_order" class="form-label">
                      @lang('doctors::app.sort_order')
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <input type="number" class="form-control" id="sort_order" name="sort_order"
                           placeholder="@lang('doctors::app.sort_order')"
                           value="{{ old('sort_order', $model->sort_order ?? \Modules\Doctors\Models\Doctor::getNextSortOrder()) }}"
                           min="0" step="1"
                           required>
                  </div>

                  <div class="mb-3 col-md-4">
                    <label for="avatar" class="form-label">@lang('doctors::app.avatar')</label>
                    <div class="input-group">
                      <input type="file" name="avatar" class="form-control" id="avatar"
                             placeholder="@lang('doctors::app.avatar')">
                    </div>
                  </div>

                  <div class="mb-3 col-md-4">
                    <label for="status" class="form-label">
                      @lang('doctors::app.status')
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <select name="status" class="form-select" id="status" required>
                      <option value="">{{ __('app.select_option') }}</option>
                      @foreach(\Modules\Doctors\Constants\Doctor\Status::all(true) as $key => $value)
                        <option
                          value="{{ $key }}" {{ old('status', $model->status ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="mb-3 col-md-12">
                    <label for="type" class="form-label">
                      @lang('doctors::app.bio')
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                   <textarea class="form-control" id="bio" name="bio" placeholder="@lang('doctors::app.bio')" required>{{ old('bio', $model->bio ?? '') }}
                   </textarea>
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
