@extends('layouts/contentNavbarLayout')

@section('title', __('doctors::app.speciality'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span class="text-muted fw-light">@lang('doctors::app.specialities')/</span>
      {{$edit ? __('app.edit') . ' '. $model->name : __('app.add') .' ' . __('doctors::app.speciality')}}
    </div>
    <div class="col-md-auto">
      @permission(\App\Support\Enum\PermissionNames::MANAGE_SPECIALITIES)
      <a href="{{ route('specialities.index') }}" class="text-white text-decoration-none">
        <button type="button" class="btn btn-primary" style="float: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
          <span class="tf-icons bx bx-arrow-back"></span> @lang('app.back')
        </button>
      </a>
      @endpermission
    </div>
  </h4>

  @if ($edit)
    <form action="{{ route('specialities.update', $model->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PATCH')
      @else
        <form action="{{ route('specialities.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @endif

          <div class="row">
            <div class="col-xl">
              <div class="card mb-4">
                <div class="row card-body">
                  <div class="mb-3 col-md-6">
                    <label for="name_ar" class="form-label">
                      {{ __('app.name') }}
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <div class="nav-align-top mb-4">
                      <ul class="nav nav-pills mb-3" role="tablist">
                        <li class="nav-item">
                          <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                  data-bs-target="#navs-pills-top-french" aria-controls="navs-pills-top-french"
                                  aria-selected="true">
                            @lang('app.french')
                          </button>
                        </li>
                        <li class="nav-item">
                          <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                  data-bs-target="#navs-pills-top-english" aria-controls="navs-pills-top-english"
                                  aria-selected="false">
                            @lang('app.english')
                          </button>
                        </li>
                        <li class="nav-item">
                          <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                  data-bs-target="#navs-pills-top-arabic" aria-controls="navs-pills-top-arabic"
                                  aria-selected="false">
                            @lang('app.arabic')
                          </button>
                        </li>
                      </ul>
                      <div class="tab-content">
                        <div class="tab-pane fade show active" id="navs-pills-top-french" role="tabpanel">
                          <input type="text"
                                 name="name[fr]"
                                 id="name_fr"
                                 class="form-control"
                                 placeholder="{{ __('app.french') }}"
                                 value="{{ old('name_fr', $edit ? $model->getTranslation('name', 'fr') : '') }}"
                                 required>
                        </div>
                        <div class="tab-pane fade" id="navs-pills-top-english" role="tabpanel">
                          <input type="text"
                                 name="name[en]"
                                 id="name_en"
                                 class="form-control"
                                 placeholder="{{ __('app.english') }}"
                                 value="{{ old('name_en', $edit ? $model->getTranslation('name', 'en') : '') }}"
                                 required>
                        </div>
                        <div class="tab-pane fade" id="navs-pills-top-arabic" role="tabpanel">
                          <input type="text"
                                 name="name[ar]"
                                 id="name_ar"
                                 class="form-control"
                                 placeholder="{{ __('app.arabic') }}"
                                 value="{{ old('name_ar', $edit ? $model->getTranslation('name', 'ar') : '') }}"
                                 required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label for="status" class="form-label">
                      {{ __('doctors::app.status') }}
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <select name="status" class="form-select" id="status" required>
                      <option value="">{{ __('app.select_option') }}</option>
                      @foreach(\Modules\Doctors\Constants\Speciality\Status::all(true) as $key => $value)
                        <option
                          value="{{ $key }}" {{ old('status', $model->status ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                      @endforeach
                    </select>
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
        @section('scripts')
          <script>
            $(document).ready(function() {
              function toggleLinkField() {
                const selectedType = $('#type').val();
                if (selectedType === 'none') {
                  $('#link').prop('disabled', true).val('');
                } else {
                  $('#link').prop('disabled', false);
                }
              }

              // Initial check
              toggleLinkField();

              // On change
              $('#type').on('change', toggleLinkField);
            });
          </script>
    @endsection
