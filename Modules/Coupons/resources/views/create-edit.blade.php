@extends('layouts/contentNavbarLayout')

@section('title', __('coupons::app.coupon'))

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('content')
    <!-- Header Actions -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1 mt-3">@lang($edit ? 'app.edit' : 'app.add') @lang('coupons::app.coupon')</h4>
            <p class="text-muted">@lang('coupons::app.coupon_info')</p>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-3">
            @permission(\App\Support\Enum\PermissionNames::MANAGE_COUPONS)
                <a href="{{ route('coupons.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i>@lang('app.back')
                </a>
            @endpermission
            <button type="submit" form="form" class="btn btn-primary">
                <i class="bx bx-save me-1"></i>@lang('app.send')
            </button>
        </div>
    </div>

    <!-- Coupon Form with Icons -->
    <div class="row">
        <!-- Basic Details Card -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">@lang('coupons::app.basic_details')</h5>
                </div>
                <div class="card-body">
                    @if ($edit)
                        <form action="{{ route('coupons.update', $model->id) }}" method="POST"
                            enctype="multipart/form-data" id="form">
                            @csrf
                            @method('PATCH')
                        @else
                            <form action="{{ route('coupons.store') }}" method="POST" enctype="multipart/form-data"
                                id="form">
                                @csrf
                    @endif

                    <div class="mb-3">
                        <label class="form-label" for="name">@lang('coupons::app.name')</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            id="name" placeholder="@lang('coupons::app.name')" value="{{ old('name', $model->name ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="code">@lang('coupons::app.code')</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                            id="code" value="{{ old('code', $model->code ?? $code) }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="discount">@lang('coupons::app.discount')</label>
                        <div class="input-group">
                            <input type="number" name="discount"
                                class="form-control @error('discount') is-invalid @enderror" id="discount"
                                placeholder="@lang('coupons::app.discount')" value="{{ old('discount', $model->discount ?? '') }}">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Card -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">@lang('coupons::app.advanced_settings')</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="start_date">@lang('coupons::app.start_date')</label>
                            <input type="date" name="start_date"
                                class="form-control @error('start_date') is-invalid @enderror dob-picker" id="start_date"
                                placeholder="{{ 'YYYY-MM-DD' }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="end_date">@lang('coupons::app.end_date')</label>
                            <input type="date" name="end_date"
                                class="form-control @error('end_date') is-invalid @enderror dob-picker" id="end_date"
                                placeholder="{{ 'YYYY-MM-DD' }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="max_uses">@lang('coupons::app.max_uses')</label>
                        <input type="number" name="max_uses" class="form-control @error('max_uses') is-invalid @enderror"
                            id="max_uses" placeholder="@lang('coupons::app.max_uses')"
                            value="{{ old('max_uses', $model->max_uses ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="status">@lang('coupons::app.status')</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror select2"
                            id="status">
                            @foreach (\Modules\Coupons\Constants\Status::all(true) as $key => $value)
                                <option value="{{ $key }}"
                                    {{ old('status', $model->status ?? '') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $("#start_date").flatpickr({
                defaultDate: "{{ old('start_date', $model->start_date ?? '') }}"
            });
            $("#end_date").flatpickr({
                defaultDate: "{{ old('end_date', $model->end_date ?? '') }}"
            });
        });
    </script>
@endsection
