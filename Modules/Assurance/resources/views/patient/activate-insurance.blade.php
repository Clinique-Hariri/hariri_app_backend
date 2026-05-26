@extends('layouts/contentNavbarLayout')

@section('title', __('assurance::app.patient'))

@section('vendor-style')
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/bs-stepper/bs-stepper.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.css')}}" />
@endsection

@section('vendor-script')
  <script src="{{asset('assets/vendor/libs/bs-stepper/bs-stepper.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/autosize/autosize.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>
@endsection

@section('page-script')
  <script src="{{asset('assets/js/form-wizard-icons.js')}}"></script>
  <script src="{{asset('assets/js/forms-extras.js')}}"></script>
@endsection

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span
        class="text-muted fw-light">@lang('app.dashboard') / {{$user->fullname}} /</span> @lang('assurance::app.activate-insurance')
    </div>
  </h4>

  <div class="row">

    <!-- Default Icons Wizard -->
    <div class="col-12 mb-4">
      <div class="bs-stepper wizard-icons wizard-icons-example mt-2">

        <div class="bs-stepper-header">
          <div class="step" data-target="#personal-info">
            <button type="button" class="step-trigger">
             <span class="bs-stepper-icon">
              <svg viewBox="0 0 54 54">
                <use xlink:href="{{asset('assets/svg/icons/form-wizard-account.svg#wizardAccount')}}"></use>
              </svg>
            </span>
              <span class="bs-stepper-label">@lang('assurance::app.personal-info')</span>
            </button>
          </div>
          <div class="line">
            <i class="bx bx-chevron-right"></i>
          </div>
          <div class="step" data-target="#wallet-init">
            <button type="button" class="step-trigger">
             <span class="bs-stepper-icon">
              <svg viewBox="0 0 54 54">
                <use xlink:href="{{asset('assets/svg/icons/wizard-checkout-payment.svg#wizardPayment')}}"></use>
              </svg>
            </span>
              <span class="bs-stepper-label">@lang('assurance::app.wallet-init')</span>
            </button>
          </div>
          <div class="line">
            <i class="bx bx-chevron-right"></i>
          </div>
          <div class="step" data-target="#contract">
            <button type="button" class="step-trigger">
            <span class="bs-stepper-icon">
              <svg viewBox="0 0 54 54">
                <use xlink:href="{{asset('assets/svg/icons/form-wizard-address.svg#wizardAddress')}}"></use>
              </svg>
            </span>
              <span class="bs-stepper-label">@lang('assurance::app.contract')</span>
            </button>
          </div>
          <div class="line">
            <i class="bx bx-chevron-right"></i>
          </div>
          <div class="step" data-target="#family-members">
            <button type="button" class="step-trigger">
            <span class="bs-stepper-icon">
              <svg viewBox="0 0 54 54">
                <use xlink:href="{{asset('assets/svg/icons/form-wizard-social-link.svg#wizardSocialLink')}}"></use>
              </svg>
            </span>
              <span class="bs-stepper-label">@lang('assurance::app.family-members')</span>
            </button>
          </div>
          <div class="line">
            <i class="bx bx-chevron-right"></i>
          </div>
          <div class="step" data-target="#review-submit">
            <button type="button" class="step-trigger">
            <span class="bs-stepper-icon">
              <svg viewBox="0 0 54 54">
                <use xlink:href="{{asset('assets/svg/icons/form-wizard-submit.svg#wizardSubmit')}}"></use>
              </svg>
            </span>
              <span class="bs-stepper-label">@lang('assurance::app.review-submit')</span>
            </button>
          </div>
        </div>
        <div class="bs-stepper-content">
          <form id="insurance-form" action="{{route('patients.activate-insurance.action', $user->id)}}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Personal Info -->
            <div id="personal-info" class="content">
              <div class="content-header mb-3">
                <h6 class="mb-0">@lang('assurance::app.personal-info')</h6>
              </div>
              <div class="row g-3">
                <div class="col-sm-6">
                  <label class="form-label" for="firstname">@lang('assurance::app.firstname')</label>
                  <input type="text" id="firstname" name="firstname" class="form-control"
                         placeholder="@lang('assurance::app.firstname')"
                         value="{{ $user->firstname }}" aria-label="{{ $user->firstname }}" />
                </div>
                <div class="col-sm-6">
                  <label class="form-label " for="lastname">@lang('assurance::app.lastname')</label>
                  <input type="text" id="lastname" name="lastname" class="form-control"
                         placeholder="@lang('assurance::app.lastname')"
                         value="{{ $user->lastname }}" aria-label="{{ $user->lastname }}" />
                </div>
                <div class="col-sm-6">
                  <label class="form-label" for="email">@lang('assurance::app.email')</label>
                  <input type="email" id="email" name="email" class="form-control"
                         placeholder="@lang('assurance::app.email')"
                         value="{{ $user->email }}" aria-label="{{ $user->email }}" />
                </div>
                <div class="col-sm-6">
                  <label class="form-label" for="phone">@lang('assurance::app.phone')</label>
                  <input type="text" id="phone" name="phone" class="form-control"
                         placeholder="@lang('assurance::app.phone')"
                         value="{{ $user->phone }}" aria-label="{{ $user->phone }}" />
                </div>
                <div class="col-sm-6">
                  <label class="form-label" for="gender">@lang('assurance::app.gender')</label>
                  <select name="gender" class="form-select" id="gender" required>
                    <option value="">@lang('app.select_option')</option>
                    @foreach(\App\Constants\Gender::all(true) as $key => $value)
                      <option
                        value="{{ $key }}" {{ old('gender', $user->gender ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-sm-6">
                  <label class="form-label" for="blood_type">@lang('assurance::app.blood-type')</label>
                  <select name="blood_type" class="form-select" id="blood_type" required>
                    <option value="">@lang('app.select_option')</option>
                    @foreach(\App\Constants\BloodType::all(true) as $key => $value)
                      <option
                        value="{{ $key }}" {{ old('blood_type', $user->blood_type ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-sm-6">
                  <label class="form-label" for="birthdate">@lang('assurance::app.date-of-birth')</label>
                  <input type="date" id="birthdate" name="birthdate" class="form-control flatpickr-basic"
                         placeholder="@lang('assurance::app.date-of-birth')"
                         value="{{ $user->birthdate ? $user->birthdate->format('Y-m-d') : '' }}"
                         aria-label="{{ $user->birthdate ? $user->birthdate->format('Y-m-d') : '' }}" />
                </div>
                <div class="col-sm-6">
                  <label class="form-label" for="full_address">@lang('assurance::app.full-address')</label>
                  <input type="text" id="full_address" name="full_address" class="form-control"
                         placeholder="@lang('assurance::app.full-address')"
                         value="{{ $user->full_address }}" aria-label="{{ $user->full_address }}" />
                </div>
                <div class="col-sm-6">
                  <label class="form-label" for="passport_number">@lang('assurance::app.passport-number')</label>
                  <input type="text" id="passport_number" name="passport_number" class="form-control"
                         placeholder="@lang('assurance::app.passport-number')"
                         value="{{ $user->passport_number }}" aria-label="{{ $user->passport_number }}" />
                </div>
                <div class="col-12 d-flex justify-content-between">
                  <button type="button" class="btn btn-label-secondary btn-prev" disabled>
                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                    <span class="align-middle d-sm-inline-block d-none">@lang('app.previous')</span>
                  </button>
                  <button type="button" class="btn btn-primary btn-next">
                    <span class="align-middle d-sm-inline-block d-none me-sm-1">@lang('app.next')</span>
                    <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                  </button>
                </div>
              </div>
            </div>
            <!-- Wallet Init -->
            <div id="wallet-init" class="content">
              <div class="content-header mb-3">
                <h6 class="mb-0">@lang('assurance::app.wallet-init')</h6>
              </div>
              <div class="row g-3">
                <div class="d-flex mb-3 gap-3">
                  <div>
                    <span class="badge bg-label-primary rounded-2">
                      <i class="bx bx-credit-card bx-md"></i>
                    </span>
                  </div>
                  <div>
                    <h4 class="mb-0">
                      <span class="align-middle">@lang('assurance::app.initial-amount')</span>
                    </h4>
                    <span>@lang('assurance::app.initial-amount-description')</span>
                  </div>
                </div>
                <div class="faq-header d-flex flex-column justify-content-center align-items-center">
                  <div class="input-wrapper my-3 input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-money bx-xs text-muted"></i></span>
                    <input type="number" class="form-control form-control-lg" placeholder="0.00"
                           name="wallet_balance" id="wallet_balance"
                           value="{{ old('wallet_balance'?? 0) }}">
                  </div>
                </div>

                <div class="col-12 d-flex justify-content-between">
                  <button type="button" class="btn btn-primary btn-prev">
                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                    <span class="align-middle d-sm-inline-block d-none">@lang('app.previous')</span>
                  </button>
                  <button type="button" class="btn btn-primary btn-next">
                    <span class="align-middle d-sm-inline-block d-none me-sm-1">@lang('app.next')</span>
                    <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                  </button>
                </div>
              </div>
            </div>
            <!-- Contract -->
            <div id="contract" class="content">
              <div class="content-header mb-3">
                <h6 class="mb-0">@lang('assurance::app.contract')</h6>
              </div>
              <div class="row g-3">
                <div class="text-center my-3">
                  <div class="mb-2">
                    <button type="button" class="btn btn-primary" id="dowanload-contract-btn">
                      <i class="bx bx-file bx-sm me-1"></i>
                      @lang('assurance::app.download-insurance-contract')
                    </button>

                    <label class="btn btn-outline-primary mb-0">
                      <i class="bx bx-upload bx-sm me-1"></i>
                      @lang('assurance::app.upload-insurance-contract')
                      <input type="file" name="contract" id="upload-contract" accept=".png,.jpg,.jpeg,.gif,.svg" class="d-none">
                    </label>
                  </div>

                  <!-- File name below buttons -->
                  <!-- This is the hidden file name + check icon -->
                  <div id="file-name" class="mt-2 small px-3 py-2 bg-light border border-success rounded text-success d-inline-flex align-items-center gap-2 d-none">
                    <i class="bx bxs-check-circle fs-5 text-success"></i>
                    <span id="uploaded-file-name" class="fw-semibold"></span>
                  </div>
                </div>

                <div class="col-12 d-flex justify-content-between">
                  <button type="button" class="btn btn-primary btn-prev">
                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                    <span class="align-middle d-sm-inline-block d-none">@lang('app.previous')</span>
                  </button>
                  <button type="button" class="btn btn-primary btn-next">
                    <span class="align-middle d-sm-inline-block d-none me-sm-1">@lang('app.next')</span>
                    <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                  </button>
                </div>
              </div>
            </div>
            <!-- Family Members -->
            <div id="family-members" class="content">
              <div class="content-header mb-3">
                <h6 class="mb-0">@lang('assurance::app.family-members')</h6>
              </div>
              <div class="row g-3">
                <div class="form-repeater">
                  <div class="col-12">
                    <div data-repeater-list="family_members">
                      <div data-repeater-item>
                        <div class="row">
                          <div class="mb-3 col-md-6">
                            <label for="firstname" class="form-label">@lang('assurance::app.firstname')</label>
                            <input type="text" name="firstname" class="form-control" id="firstname"
                                   placeholder="@lang('assurance::app.firstname')"
                                   value="">
                          </div>
                          <div class="mb-3 col-md-6">
                            <label for="lastname" class="form-label">@lang('assurance::app.lastname')</label>
                            <input type="text" name="lastname" class="form-control" id="lastname"
                                   placeholder="@lang('assurance::app.lastname')"
                                   value="">
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="phone" class="form-label">@lang('assurance::app.phone')</label>
                            <input type="text" name="phone" class="form-control phone-mask" id="phone"
                                   placeholder="@lang('assurance::app.phone')" value="">
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="gender" class="form-label">@lang('assurance::app.gender')</label>
                            <select name="gender" class="form-select" id="gender" required>
                              <option value="">@lang('app.select_option')</option>
                              @foreach(\App\Constants\Gender::all(true) as $key => $value)
                                <option
                                  value="{{ $key }}">{{ $value }}
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
                                  value="{{ $key }}">{{ $value }}
                                </option>
                              @endforeach
                            </select>
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="birthdate" class="form-label">@lang('assurance::app.birthdate')</label>
                            <input type="date" name="birthdate" class="form-control date-mask" id="birthdate"
                                   placeholder="@lang('assurance::app.birthdate')"
                                   value="">
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="family_relation"
                                   class="form-label">{{ __('assurance::app.family_relation') }}</label>
                            <input type="text" name="family_relation" class="form-control" id="family_relation"
                                   placeholder="{{ __('assurance::app.family_relation') }}"
                                   value="">
                          </div>

                          <div class="mb-3 col-md-6">
                            <label for="avatar" class="form-label">{{ __('assurance::app.avatar') }}</label>
                            <div class="input-group">
                              <input type="file" name="avatar" class="form-control" id="avatar"
                                     placeholder="{{ __('assurance::app.avatar') }}">
                            </div>
                          </div>

                          <div class="form-group" style="text-align: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
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

                <div class="col-12 d-flex justify-content-between">
                  <button type="button" class="btn btn-primary btn-prev">
                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                    <span class="align-middle d-sm-inline-block d-none">@lang('app.previous')</span>
                  </button>
                  <button type="button" class="btn btn-primary btn-next">
                    <span class="align-middle d-sm-inline-block d-none me-sm-1">@lang('app.next')</span>
                    <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                  </button>
                </div>
              </div>
            </div>
            <!-- Review -->
            <div id="review-submit" class="content">
              <div class="row mb-3">
                <div class="col-12 col-lg-8 mx-auto text-center mb-3">
                  <div class="mb-2">
                    <i class="bx bx-shield-plus text-primary" style="font-size: 8rem;"></i>
                  </div>
                  <p class="text-primary">@lang('assurance::app.insurance-activation-review-message')</p>
                </div>
                <!-- Confirmation details -->
                <div class="col-12">
                  <ul class="list-group list-group-horizontal-md">
                    <li class="list-group-item flex-fill p-4 text-heading">
                      <h6 class="d-flex align-items-center gap-1"><i
                          class="bx bx-user"></i>@lang('assurance::app.personal-info')</h6>
                      <ul class="list-unstyled">
                        <li id="preview_firstname"></li>
                        <li id="preview_lastname"></li>
                        <li id="preview_email"></li>
                        <li id="preview_phone"></li>
                      </ul>
                    </li>
                    <li class="list-group-item flex-fill p-4 text-heading">
                      <!-- medical info -->
                      <h6 class="d-flex align-items-center gap-1"><i
                          class="bx bx-user-check"></i>@lang('assurance::app.medical-info')</h6>
                      <ul class="list-unstyled">
                        <span>@lang('assurance::app.blood-type') <li id="preview_blood_type"></li></span>
                      </ul>
                    </li>
                    <li class="list-group-item flex-fill p-4 text-heading">
                      <h6 class="d-flex align-items-center gap-1"><i
                          class="bx bx-credit-card"></i>@lang('assurance::app.wallet-init')</h6>
                      <ul class="list-unstyled">
                        <span><li id="preview_wallet_balance"></li> FCFA</span>
                      </ul>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-12 d-flex justify-content-between">
                <button type="button" class="btn btn-primary btn-prev">
                  <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                  <span class="align-middle d-sm-inline-block d-none">@lang('app.previous')</span>
                </button>
                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#confirmation-modal">
                  <i class="bx bx-shield-plus fs-5"></i>
                  @lang('assurance::app.activate-insurance')
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /Default Icons Wizard -->
  </div>

  <!-- Confirmation Modal -->
  <div class="modal fade" id="confirmation-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="fw-bold py-1 mb-1">@lang('assurance::app.activate-insurance')</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success mb-4">
            <h5 class="text-center">@lang('assurance::app.activate-insurance-confirmation')</h5>
            <p class="text-center mb-0">@lang('app.modal.confirm.text')</p>
          </div>
          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="confirmed" required>
              <label class="form-check-label" for="confirmed">
                @lang('app.modal.confirm.checkbox')
              </label>
            </div>
          </div>
          <div class="d-flex justify-content-center gap-2">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
              @lang('app.cancel')
            </button>
            <button type="button" class="btn btn-success" id="confirm-submit-btn">
              @lang('assurance::app.activate-insurance')
            </button>

            {{--            <button type="submit" class="btn btn-success">--}}
            {{--              @lang('assurance::app.activate-insurance')--}}
            {{--            </button>--}}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function() {
      $('#confirm-submit-btn').on('click', function() {
        const isChecked = $('#confirmation-modal input[name="confirmed"]').is(':checked');
        if (isChecked) {
          $('#insurance-form').submit();
        } else {
          alert('Please confirm before submitting.');
        }
      });
    });

    $('#dowanload-contract-btn').on('click', function(e) {
      e.preventDefault();
      const userId = "{{ $user->id }}";
      const baseUrl = "{{ route('patients.generate-insurance-contract', ':id') }}".replace(':id', userId);

      const queryParams = new URLSearchParams({
        firstname: $('#firstname').val(),
        lastname: $('#lastname').val(),
        birthdate: $('#birthdate').val(),
        full_address: $('#full_address').val(),
        phone: $('#phone').val(),
        wallet_balance: $('#wallet_balance').val(),
        passport_number: $('#passport_number').val(),
      });

      const fullUrl = `${baseUrl}?${queryParams.toString()}`;

      window.open(fullUrl, '_blank');
    });

    // Code to populate the review step with user input
    const step = $('#review-submit');

    function populatePreview() {
      $('[name]').each(function() {
        const name = $(this).attr('name');
        const value = $(this).val();
        $(`#preview_${name}`).text(value);
      });
    }

    // Run once when the step becomes active
    const interval = setInterval(() => {
      if (step.hasClass('active')) {
        populatePreview();
      }
    }, 200);

    $('#upload-contract').on('change', function () {
      const file = this.files[0];
      if (file) {
        $('#uploaded-file-name').text(file.name);
        $('#file-name').removeClass('d-none');
      }
    });


  </script>

@endsection
