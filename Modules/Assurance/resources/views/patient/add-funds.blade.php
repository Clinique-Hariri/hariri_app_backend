@extends('layouts/contentNavbarLayout')

@section('title', __('assurance::app.add-funds'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span
        class="text-muted fw-light">@lang('assurance::app.add-funds') /</span> {{ $user->fullname }}
    </div>
    <div class="col-md-auto">
      @permission(\App\Support\Enum\PermissionNames::MANAGE_PATIENTS)
      <a href="{{ route('patients.index') }}" class="text-white text-decoration-none">
        <button type="button" class="btn btn-primary" style="float: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
          <span class="tf-icons bx bx-arrow-back"></span> @lang('app.back')
        </button>
      </a>
      @endpermission
    </div>
  </h4>

  {{-- User Information Card --}}
  <div class="row mb-4">
    <div class="col-xl">
      <div class="card">
        <div class="card-body">
          <div class="row text-center text-lg-start align-items-center gy-3">

            {{-- Left: Avatar and User Info --}}
            <div class="col-12 col-lg-4 d-flex align-items-center justify-content-center justify-content-lg-start">
              <div class="avatar avatar-lg me-3 bg-label-secondary rounded-2">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->fullname }}" class="rounded-2" />
              </div>
              <div class="d-flex flex-column">
                <h6 class="mb-0 text-body text-nowrap">{{ $user->fullname }}</h6>
                <small class="text-muted text-truncate">{{ $user->email }}</small>
              </div>
            </div>

            {{-- Centered Insurance Number and QR Code --}}
            <div class="col-12 col-lg-4 d-flex flex-column align-items-center">
              @if($user->is_insured)
                <h4 class="text-primary fw-bold mb-2">{{ $user->insurance_number }}</h4>
                <div class="p-2 rounded bg-light border border-primary">
                  <img
                    src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($user->insurance_number) }}&color=115-103-240&bgcolor=255-255-255"
                    alt="QR Code"
                    width="60"
                    height="60"
                  />
                </div>
              @endif
            </div>

            {{-- Right: Wallet Info and Button --}}
            <div class="col-12 col-lg-4 d-flex justify-content-center justify-content-lg-end">
              <div class="d-flex flex-column align-items-center text-center">
                <span class="fw-semibold text-muted">@lang('assurance::app.wallet_balance')</span>
                <h4 class="text-primary fw-bold mb-2">{{ $user->wallet_balance }} @lang('app.DZD')</h4>
              </div>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-xl">
      <div class="card">
        <form id="addFundsForm" action="{{ route('patients.add-funds', $user->id) }}" method="POST">
          @csrf
          <div class="card-body">
            <div class=" d-flex justify-content-center">
              <div class="col-md-6">
                <label class="form-label">@lang('assurance::app.amount')</label>
                <div class="input-group input-group-lg">
                  <input
                    type="number"
                    class="form-control"
                    name="amount"
                    placeholder="@lang('assurance::app.amount')"
                    value="{{ old('amount') }}"
                    min="0"
                    step="0.5"
                    required
                  >
                  <span class="input-group-text">@lang('app.DZD')</span>
                </div>
              </div>
            </div>
            <div class="form-group" style="text-align: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmAddFundsModal">
                {{ __('app.send') }}
              </button>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="confirmAddFundsModal" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="fw-bold py-1 mb-1">@lang('assurance::app.add-funds')</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-primary mb-4">
              <h5 class="text-center">@lang('app.modal.confirm.question')</h5>
              <p class="text-center mb-0">@lang('app.modal.confirm.text')</p>
            </div>

              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="confirmed" id="confirmCheckbox" required>
                  <label class="form-check-label" for="confirmed">
                    @lang('app.modal.confirm.checkbox')
                  </label>
                </div>
              </div>

            <div class="d-flex justify-content-center gap-2">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                @lang('app.cancel')
              </button>
              <button type="button" class="btn btn-primary" onclick="if(document.getElementById('confirmCheckbox').checked) { document.getElementById('addFundsForm').submit(); }">
                @lang('app.submit')
              </button>
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection
