@extends('layouts/contentNavbarLayout')

@section('title', __('products::app.discount'))

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
@endsection

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1 mt-3">{{ __('products::app.discount_info') }}</h4>
            <p class="text-muted">{{ $product->name }}</p>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-3">
            @permission(\App\Support\Enum\PermissionNames::MANAGE_PRODUCTS)
                <a href="{{ route('discounts.index', $product->id) }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
                </a>
            @endpermission
            <button type="submit" form="form" class="btn btn-primary">
                <i class="bx bx-save me-1"></i>{{ __('app.send') }}
            </button>
        </div>
    </div>

    @if ($edit)
        <form action="{{ route('discounts.update', $model->id) }}" method="POST" enctype="multipart/form-data"
            id="form">
            @csrf
            @method('PATCH')
        @else
            <form action="{{ route('discounts.store') }}" method="POST" enctype="multipart/form-data" id="form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
    @endif

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="row card-body">

                    <div class="mb-3 col-md-6">
                        <label for="start_date" class="form-label">{{ __('products::app.start_date') }}</label>
                        <input type="date" name="start_date" class="form-control @if($errors->has('start_date') || $errors->has('date')) is-invalid @endif dob-picker" id="start_date"
                            placeholder="{{ 'YYYY-MM-DD' }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="end_date" class="form-label">{{ __('products::app.end_date') }}</label>
                        <input type="date" name="end_date" class="form-control @if($errors->has('end_date') || $errors->has('date')) is-invalid @endif dob-picker" id="end_date"
                            placeholder="{{ 'YYYY-MM-DD' }}">
                    </div>

                    <div class="mb-3 col-md-6">
                      <label for="discount" class="form-label">{{ __('products::app.discount') }}</label>
                      <input type="number" name="discount" class="form-control @error('discount') is-invalid @enderror" id="discount"
                          placeholder="{{ __('products::app.discount') }}"
                          value="{{ old('discount', $model->discount ?? '') }}">
                  </div>

                </div>
            </div>
        </div>
    </div>
    </form>
@endsection

@section('scripts')
  <script>
    $(document).ready(function () {
      $("#start_date").flatpickr({
        defaultDate: "{{ old('start_date' , $model->start_date ?? '') }}"
      });
      $("#end_date").flatpickr({
        defaultDate: "{{ old('end_date' , $model->end_date ?? '') }}"
      });
    });
  </script>
@endsection
