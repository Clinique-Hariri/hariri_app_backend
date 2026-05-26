@extends('layouts/contentNavbarLayout')

@section('title', __('assurance::app.patient'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span
        class="text-muted fw-light">@lang('app.show') /</span> {{ $user->fullname }}
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
                <a href="{{ route('patients.add-funds', $user->id) }}"
                   class="btn btn-primary d-flex align-items-center justify-content-center mt-2">
                  <i class="bx bx-wallet me-1"></i>
                  @lang('assurance::app.add-funds')
                </a>
              </div>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- User Details --}}
  <div class="row mb-4">
    <div class="col-xl">
      <div class="card">
        <div class="row card-body">
          <div class="mb-3 col-md-4">
            <label class="form-label">@lang('assurance::app.passport-number')</label>
            <input type="text" class="form-control" value="{{ $user->passport_number }}" readonly>
          </div>
          <div class="mb-3 col-md-4">
            <label class="form-label">@lang('assurance::app.birthdate')</label>
            <input type="text" class="form-control"
                   value="{{ $user->birthdate ? $user->birthdate->format('Y-m-d') : '' }}" readonly>
          </div>
          <div class="mb-3 col-md-4">
            <label class="form-label">@lang('assurance::app.phone')</label>
            <input type="text" class="form-control" value="{{ $user->phone }}" readonly>
          </div>
          <div class="mb-3 col-md-4">
            <label class="form-label">@lang('assurance::app.gender')</label>
            <input type="text" class="form-control" value="{{ \App\Constants\Gender::get_name($user->gender) }}"
                   readonly>
          </div>
          <div class="mb-3 col-md-4">
            <label class="form-label">@lang('assurance::app.blood-type')</label>
            <input type="text" class="form-control" value="{{ $user->blood_type? \App\Constants\BloodType::get_name($user->blood_type) : '-'}}"
                   readonly>
          </div>
          <div class="mb-3 col-md-4">
            <label class="form-label">@lang('assurance::app.address')</label>
            <input type="text" class="form-control" value="{{ $user->full_address }}" readonly>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Family Members --}}
  <div class="card mb-4">
    <h4 class="card-header">@lang('assurance::app.family-members')</h4>
    <div class="card-datatable table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
        <tr>
          <th>#</th>
          <th>@lang('assurance::app.name')</th>
          <th>@lang('assurance::app.birthdate')</th>
          <th>@lang('assurance::app.phone')</th>
          <th>@lang('assurance::app.gender')</th>
          <th>@lang('assurance::app.blood-type')</th>
          <th>@lang('assurance::app.family_relation')</th>
        </tr>
        </thead>
        <tbody class="table-border-bottom-0">
        @forelse($user->familyMembers as $member)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $member->fullname }}</td>
            <td>{{ $member->birthdate ? $member->birthdate->format('Y-m-d') : '-' }}</td>
            <td>{{ $member->phone }}</td>
            <td>{{ \App\Constants\Gender::get_name($member->gender) }}</td>
            <td>{{ $member->blood_type? \App\Constants\BloodType::get_name($member->blood_type) : '' }}</td>
            <td>{{ $member->family_relation }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted">
              @lang('assurance::app.no-family-members')
            </td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>


  {{-- Transactions --}}
  <div class="card mb-4">
    <h4 class="card-header">@lang('assurance::app.transactions')</h4>
    <div class="card-datatable table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
        <tr>
          <th>#</th>
          <th>@lang('assurance::app.transaction-number')</th>
          <th>@lang('assurance::app.amount')</th>
          <th>@lang('assurance::app.transaction-type')</th>
          <th>@lang('assurance::app.date')</th>
          <th>@lang('assurance::app.status')</th>
        </tr>
        </thead>
        <tbody class="table-border-bottom-0">
        @forelse($user->transactions as $transaction)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $transaction->transaction_number }}</td>
            <td>{{ $transaction->amount }} @lang('app.DZD')</td>
            <td>
              <span class="text-truncate d-flex align-items-center">
                <span
                  class="badge badge-center rounded-pill w-px-30 h-px-30 me-2 {{ $transaction->type === 'credit' ? 'bg-label-success' : 'bg-label-danger' }}">
                  <i
                    class="fas {{ $transaction->type === 'credit' ? 'fa-arrow-up' : 'fa-arrow-down' }} text-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}"></i>
                </span>
                {{ \Modules\Transactions\Constants\Type::get_name($transaction->type) }}
              </span>
            </td>
            <td>{{ $transaction->created_at?->format('Y-m-d') }}</td>
            <td>
              <span class="badge {{ $transaction->status === 'completed' ? 'bg-label-success' : 'bg-label-warning' }}">
                {{ \Modules\Transactions\Constants\Status::get_name($transaction->status) }}
              </span>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted">@lang('assurance::app.no-transactions')</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @php
    use App\Models\User;
    use Illuminate\Support\Str;
  @endphp
  {{-- Insurance Contract --}}
  @if($user->is_insured)
    <div class="card">
      <h4 class="card-header">@lang('assurance::app.insurance-contract')</h4>
      <div class="card-body text-center">

        @php
          $contract = $user->getFirstMedia(User::CONTRACT);
        @endphp

        @if($contract)
          <div class="border rounded p-3 bg-light d-inline-block w-100">
            @if(Str::endsWith($contract->mime_type, 'pdf'))
              <embed src="{{ $contract->getUrl() }}" type="application/pdf" width="100%" height="500px"
                     class="rounded border w-100" />
            @else
              <img src="{{ $contract->getUrl() }}" class="img-fluid rounded border" alt="Contract Preview">
            @endif

            <div class="mt-2 text-muted small">
              <i class="bx bxs-file-pdf text-success me-1"></i>
              {{ $contract->file_name }}
            </div>

            <a href="{{ $contract->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary mt-3">
              <i class="bx bx-show me-1"></i>
              @lang('assurance::app.view-full')
            </a>
          </div>
        @else
          <p class="text-muted">@lang('assurance::app.no-contract-uploaded')</p>
        @endif

      </div>
    </div>
  @endif

@endsection
