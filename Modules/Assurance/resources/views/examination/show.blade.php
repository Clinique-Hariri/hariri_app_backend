@extends('layouts/contentNavbarLayout')

@section('title', __('assurance::app.examination_details'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span class="text-muted fw-light">@lang('app.show') /</span> @lang('assurance::app.examination') #{{ $examination->id }}
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

  <div class="row mb-4">
    <div class="col-xl">
      <div class="card">
        <div class="row card-body">
          <div class="mb-3 col-md-3">
            <label class="form-label">@lang('assurance::app.family-member')</label>
            <input type="text" class="form-control" value="{{ $examination->familyMember?->fullname }}" readonly>
          </div>
          <div class="mb-3 col-md-3">
            <label class="form-label">@lang('assurance::app.total_price')</label>
            <input type="text" class="form-control" value="{{ $examination->total_price }} @lang('app.DZD')" readonly>
          </div>
          <div class="mb-3 col-md-3">
            <label class="form-label">@lang('assurance::app.date')</label>
            <input type="text" class="form-control" value="{{ $examination->date?->format('Y-m-d') }}" readonly>
          </div>
          <div class="mb-3 col-md-3">
            <label class="form-label">@lang('assurance::app.is_paid')</label>
            <input type="text" class="form-control"
                   value="{{ $examination->is_paid ? __('assurance::app.pay-wallet') : __('app.no') }}" readonly>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <h5 class="card-header">@lang('assurance::app.services')</h5>
    <div class="card-datatable table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
        <tr>
          <th>#</th>
          <th>@lang('assurance::app.service')</th>
          <th>@lang('assurance::app.price')</th>
          <th>@lang('assurance::app.image')</th>
          <th>@lang('assurance::app.pdfs')</th>
        </tr>
        </thead>
        <tbody>
        @forelse($examination->examinationServices as $service)
          @php
            $imageUrl = $service->getFirstMediaUrl(\Modules\Assurance\Models\ExaminationService::IMAGE);
            $pdfs = $service->getMedia(\Modules\Assurance\Models\ExaminationService::PDF);
          @endphp
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $service->service_name }}</td>
            <td>{{ $service->service_price }} @lang('app.DZD')</td>
            <td>
              @if($imageUrl)
                <a href="{{ $imageUrl }}" target="_blank">
                  <img src="{{ $imageUrl }}" alt="{{ $service->service_name }}"
                       class="rounded-2" style="height: 60px; width: 60px; object-fit: cover;">
                </a>
              @else
                <span class="text-muted">-</span>
              @endif
            </td>
            <td>
              @if($pdfs->count())
                <div class="d-flex flex-wrap gap-1">
                  @foreach($pdfs as $pdf)
                    <a href="{{ $pdf->getUrl() }}" target="_blank"
                       class="btn btn-sm btn-label-primary">
                      <i class="bx bxs-file-pdf me-1"></i>{{ $pdf->file_name }}
                    </a>
                  @endforeach
                </div>
              @else
                <span class="text-muted">@lang('assurance::app.no_pdfs')</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted">-</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
