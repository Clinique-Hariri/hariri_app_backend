@extends('layouts/contentNavbarLayout')

@section('title', __('assurance::app.service'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span class="text-muted fw-light">@lang('assurance::app.services')/</span>
      {{$edit ? __('app.edit') . ' '. $model->name : __('app.add') .' ' . __('assurance::app.service')}}
    </div>
    <div class="col-md-auto">
      @permission(\App\Support\Enum\PermissionNames::MANAGE_SERVICES)
      <a href="{{ route('services.index') }}" class="text-white text-decoration-none">
        <button type="button" class="btn btn-primary" style="float: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
          <span class="tf-icons bx bx-arrow-back"></span> @lang('app.back')
        </button>
      </a>
      @endpermission
    </div>
  </h4>

  @if ($edit)
    <form action="{{ route('services.update', $model->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PATCH')
      @else
        <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @endif

          <div class="row">
            <div class="col-xl">
              <div class="card mb-4">
                <div class="row card-body">
                  <div class="mb-3 col-md-6">
                    <label class="form-label">
                      {{ __('app.name') }}
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           class="form-control"
                           placeholder="{{ __('assurance::app.name') }}"
                           value="{{ old('name', $edit ? $model->name : null) }}"
                           required>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label class="form-label">
                      {{ __('assurance::app.price') }}
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <div class="input-group">
                      <input type="number"
                             name="price"
                             id="price"
                             class="form-control"
                             placeholder="{{ __('assurance::app.price') }}"
                             value="{{ old('price', $edit ? $model->price : null) }}"
                             min="0"
                             required>
                      <span class="input-group-text">@lang('app.DZD')</span>
                    </div>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label for="status" class="form-label">
                      {{ __('assurance::app.status') }}
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <select name="status" class="form-select" id="status" required>
                      <option value="">{{ __('app.select_option') }}</option>
                      @foreach(\Modules\Assurance\Constants\MedicalService\Status::all(true) as $key => $value)
                        <option
                          value="{{ $key }}" {{ old('status', $model->status ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label for="image" class="form-label">
                      {{ __('assurance::app.image') }}
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <div class="input-group">
                      <input type="file" name="image" class="form-control" id="ad_image"
                             placeholder="{{ __('assurance::app.image') }}" {{$edit? '' : 'required'}}>
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
