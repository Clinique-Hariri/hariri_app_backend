@extends('layouts/contentNavbarLayout')

@section('title', __('assurance::app.examination'))

@section('vendor-style')
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.css')}}" />
@endsection
@section('vendor-script')
  <script src="{{asset('assets/vendor/libs/autosize/autosize.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>
@endsection
@section('page-script')
  <script src="{{asset('assets/js/forms-extras.js')}}"></script>
@endsection

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span
        class="text-muted fw-light">{{ __($edit ? 'app.edit' : 'app.add') }} / {{$edit? $user->fullname.'/':''}}</span> @lang('assurance::app.examinations')
    </div>
    <div class="col-md-auto">
      @permission(\App\Support\Enum\PermissionNames::MANAGE_PATIENTS)
      <a href="{{ route('patients.examinations.index', $user->id) }}" class="text-white text-decoration-none">
        <button type="button" class="btn btn-primary" style="float: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
          <span class="tf-icons bx bx-arrow-back"></span> @lang('app.back')
        </button>
      </a>
      @endpermission
    </div>
  </h4>

  <div class="row mb-4" id="user-details">
    <div class="col-lg-12">
      <div class="card">
        <h4 class="card-header">@lang('assurance::app.patient')</h4>
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">

            {{-- Left: Avatar and User Info --}}
            <div class="d-flex align-items-center">
              <div class="avatar avatar-lg me-3 bg-label-secondary rounded-2">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->fullname }}" class="rounded-2" />
              </div>
              <div class="d-flex flex-column">
                <h6 class="mb-0 text-body text-nowrap">{{ $user->fullname }}</h6>
                <small class="text-muted text-truncate">{{ $user->email }}</small>
              </div>
            </div>

            {{-- Right: Wallet + Action --}}


            <div class="d-flex flex-column align-items-center text-center">
              <span class="fw-semibold text-muted">@lang('assurance::app.wallet_balance')</span>
              <h4 class="text-primary fw-bold mb-2">{{ $user->wallet_balance }} @lang('app.DZD')</h4>
              <a href="{{ route('patients.add-funds', $user->id) }}" class="btn btn-primary d-flex align-items-center mt-2">
                <i class="bx bx-wallet me-1"></i>
                @lang('assurance::app.add-funds')
              </a>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>

  @if ($edit)
    <form action="{{ route('patients.examinations.update', [$user->id, $model->id]) }}" method="POST"
          enctype="multipart/form-data">
      @csrf
      @method('PATCH')
      @else
        <form action="{{ route('patients.examinations.store', $user->id) }}" method="POST"
              enctype="multipart/form-data">
          @csrf
          @endif

          <div class="row">
            <div class="col-xl">
              <div class="card mb-4">
                <div class="row card-body">
                  <div class="mb-3 col-md">
                    <label class="form-label">@lang('assurance::app.family-member')<span class="text-danger fs-6 ms-1">*</span></label>
                    <select id="family-member" name="family_member_id" class="form-select" required>
                      <option value="" disabled {{ empty($family_member_id) ? 'selected' : '' }}>
                        @lang('app.select_option')
                      </option>
                      @foreach(\Modules\Assurance\Models\FamilyMember::
                        where('user_id', $user->id)
                        ->where('status',\Modules\Assurance\Constants\FamilyMember\Status::ACTIVE)->get() as $familyMember)
                         )
                        <option value="{{ $familyMember->id }}" {{ $family_member_id == $familyMember->id ? 'selected' : '' }}>
                          {{ $familyMember->fullname }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3 col-md">
                    <label class="form-label">@lang('assurance::app.price')<span class="text-danger fs-6 ms-1">*</span></label>
                    <div class="input-group">
                      <input type="number"
                             name="total_price"
                             id="total_price"
                             class="form-control"
                             placeholder="@lang('assurance::app.price')"
                             value="{{ old('total_price', $edit ? $model->total_price : null) }}"
                             min="0"
                             step="0.01"
                             required
                        {{$edit ? 'disabled' : ''}}
                      >
                      <span class="input-group-text">@lang('app.DZD')</span>
                    </div>
                  </div>

                  <div class="mb-3 col-md">
                    <label class="form-label">@lang('assurance::app.date')<span
                        class="text-danger fs-6 ms-1">*</span></label>
                    <input type="date" name="date" class="form-control date-mask" id="date"
                           placeholder="@lang('assurance::app.date')"
                           value="{{ old('date', $edit && $model->date ? $model?->date->format('Y-m-d') : '') }}"
                           required
                      {{$edit ? 'disabled' : ''}}
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-repeater row">
            <div class="col-12">
              <div class="card">
                <h5 class="card-header">@lang('assurance::app.services')</h5>
                <div class="card-body">
                  <div data-repeater-list="services">
                    <div data-repeater-item>
                      <div class="row">
                        <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
                          <label class="form-label" for="form-repeater-1-1">@lang('assurance::app.service')</label>
                          <select id="form-repeater-1-1" name="service_id" class="form-select">
                            <option value="" selected disabled>@lang('app.select_option')</option>
                            @foreach(\Modules\Assurance\Models\MedicalService::where('status', \Modules\Assurance\Constants\MedicalService\Status::ACTIVE)->get() as $service)
                              <option value="{{ $service->id }}"
                                      data-name="{{ $service->name }}"
                                      data-price="{{ $service->price }}">
                                {{ $service->name }}
                              </option>
                            @endforeach
                          </select>
                        </div>
                        <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
                          <label class="form-label" for="form-repeater-1-2">@lang('assurance::app.name')<span class="text-danger fs-6 ms-1">*</span></label>
                          <input type="text" id="form-repeater-1-2" name="service_name" class="form-control"
                                 placeholder="@lang('assurance::app.name')" />
                        </div>
                        <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
                          <label class="form-label" for="form-repeater-2-3">@lang('assurance::app.price')<span class="text-danger fs-6 ms-1">*</span></label>
                          <div class="input-group">
                            <input type="number"
                                   name="service_price"
                                   id="form-repeater-2-3"
                                   class="form-control"
                                   placeholder="@lang('assurance::app.price')"
                                   min="0"
                                   step="0.01"
                                   required
                            >
                            <span class="input-group-text">@lang('app.DZD')</span>
                          </div>
                        </div>
                        <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
                          <label class="form-label">@lang('assurance::app.image')</label>
                          <div class="image-picker d-flex align-items-center gap-1 flex-wrap" style="min-height: 38px;">
                            <img class="image-picker-preview rounded-2 bg-label-secondary" src="" alt=""
                                 style="height: 36px; width: 36px; object-fit: cover; display: none;">
                            <button type="button" class="btn btn-outline-primary image-picker-choose">
                              <i class="bx bx-image-add"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger image-picker-remove" style="display: none;">
                              <i class="bx bx-trash"></i>
                            </button>
                            <input type="file" name="image" class="image-picker-input" accept="image/*" hidden>
                          </div>
                        </div>
                        <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
                          <label class="form-label">@lang('assurance::app.pdfs')</label>
                          <div class="pdf-picker d-flex align-items-center gap-1 flex-wrap" style="min-height: 38px;">
                            <button type="button" class="btn btn-outline-primary pdf-picker-choose">
                              <i class="bx bxs-file-pdf"></i>
                            </button>
                            <div class="pdf-picker-list d-flex flex-wrap gap-1"></div>
                            <input type="file" name="pdfs" class="pdf-picker-input" accept="application/pdf" multiple hidden>
                          </div>
                        </div>
                        <div class="mb-3 col-lg-6 col-xl-2 col-12 d-flex align-items-center mb-0">
                          <button type="button" class="btn btn-label-danger mt-4" data-repeater-delete>
                            <i class="bx bx-x me-1"></i>
                            <span class="align-middle">@lang('app.delete')</span>
                          </button>
                        </div>
                      </div>
                      <hr>
                    </div>
                  </div>
                  <div class="mb-0">
                    <button type="button" class="btn btn-primary" data-repeater-create>
                      <i class="bx bx-plus me-1"></i>
                      <span class="align-middle">@lang('app.add')</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="form-group" style="text-align: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
            <button type="submit" class="btn btn-primary">{{ __('app.send') }}</button>
          </div>
        </form>
        @endsection
        @section('scripts')
          <script>
            $(document).ready(function() {
              updateTotalPrice();

              // When a service is selected, fill name/price and refresh the total.
              $(document).on('change', 'select[name^="services"][name$="[service_id]"]', function() {
                const $option = $(this).find('option:selected');
                const name = $option.data('name') || '';
                const price = $option.data('price') || '';

                const $repeaterItem = $(this).closest('[data-repeater-item]');
                $repeaterItem.find('input[name$="[service_name]"]').val(name);
                $repeaterItem.find('input[name$="[service_price]"]').val(price);
                updateTotalPrice();
              });

              // Recalculate the total whenever a service price is edited manually.
              $(document).on('input change', 'input[name^="services"][name$="[service_price]"]', updateTotalPrice);

              // Custom image picker.
              $(document).on('click', '.image-picker-choose', function() {
                $(this).closest('.image-picker').find('.image-picker-input').trigger('click');
              });

              $(document).on('change', '.image-picker-input', function() {
                const $picker = $(this).closest('.image-picker');
                const $preview = $picker.find('.image-picker-preview');
                const $remove = $picker.find('.image-picker-remove');

                if (this.files && this.files[0]) {
                  const reader = new FileReader();
                  reader.onload = function(e) {
                    $preview.attr('src', e.target.result).show();
                    $remove.show();
                  };
                  reader.readAsDataURL(this.files[0]);
                }
              });

              $(document).on('click', '.image-picker-remove', function() {
                const $picker = $(this).closest('.image-picker');
                $picker.find('.image-picker-input').val('');
                $picker.find('.image-picker-preview').attr('src', '').hide();
                $(this).hide();
              });

              // Custom multi-file PDF picker.
              $(document).on('click', '.pdf-picker-choose', function() {
                $(this).closest('.pdf-picker').find('.pdf-picker-input').trigger('click');
              });

              $(document).on('change', '.pdf-picker-input', function() {
                renderPdfList($(this).closest('.pdf-picker'));
              });

              $(document).on('click', '.pdf-picker-remove', function() {
                const $picker = $(this).closest('.pdf-picker');
                const $input = $picker.find('.pdf-picker-input');
                const index = $(this).data('index');
                const dataTransfer = new DataTransfer();

                Array.from($input[0].files).forEach(function(file, i) {
                  if (i !== index) {
                    dataTransfer.items.add(file);
                  }
                });

                $input[0].files = dataTransfer.files;
                renderPdfList($picker);
              });

              function renderPdfList($picker) {
                const $input = $picker.find('.pdf-picker-input');
                const $list = $picker.find('.pdf-picker-list');
                const files = Array.from($input[0].files || []);

                $list.empty();
                files.forEach(function(file, index) {
                  $list.append(
                    $('<span>')
                      .addClass('badge bg-label-secondary d-inline-flex align-items-center gap-1')
                      .append($('<i>').addClass('bx bxs-file-pdf'))
                      .append(
                        $('<span>')
                          .text(file.name)
                          .attr('title', file.name)
                          .css({
                            maxWidth: '90px',
                            overflow: 'hidden',
                            textOverflow: 'ellipsis',
                            whiteSpace: 'nowrap'
                          })
                      )
                      .append(
                        $('<button>')
                          .attr('type', 'button')
                          .addClass('btn-close pdf-picker-remove')
                          .data('index', index)
                      )
                  );
                });
              }

              function updateTotalPrice() {
                let total = 0;
                $('input[name^="services"][name$="[service_price]"]').each(function() {
                  const val = parseFloat($(this).val());
                  if (!isNaN(val)) total += val;
                });
                $('input[name="total_price"]').val(total.toFixed(2));
              }
            });
          </script>


    @endsection
