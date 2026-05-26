@extends('layouts/contentNavbarLayout')

@section('title', __('products::app.stock'))

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1 mt-3">{{ __('products::app.batch_info') }}</h4>
            <p class="text-muted">{{ $product->name }}</p>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-3">
            @permission(\App\Support\Enum\PermissionNames::MANAGE_PRODUCTS)
                <a href="{{ route('stocks.index', $product->id) }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
                </a>
            @endpermission
            <button type="submit" form="form" class="btn btn-primary">
                <i class="bx bx-save me-1"></i>{{ __('app.send') }}
            </button>
        </div>
    </div>

    @if ($edit)
        <form action="{{ route('stocks.update', $model->id) }}" method="POST" enctype="multipart/form-data" id="form">
            @csrf
            @method('PATCH')
        @else
            <form action="{{ route('stocks.store') }}" method="POST" enctype="multipart/form-data" id="form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
    @endif
    @if (!$edit && isset($product))
        <input type="hidden" name="product_id" value="{{ $product->id }}">
    @endif
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="row card-body">
                    @if (!isset($product))
                        <div class="mb-3 col-md-6">
                            <label for="product_id" class="form-label">{{ __('products::app.product') }}</label>
                            <select name="product_id" id="product_id" class="form-select">
                                <option value="">{{ __('app.select') }}</option>
                                @foreach ($products as $productItem)
                                    <option value="{{ $productItem->id }}"
                                        {{ old('product_id', $edit ? $model->product_id : '') == $productItem->id ? 'selected' : '' }}>
                                        {{ $productItem->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-3 col-md-6">
                        <label for="purchase_price" class="form-label">{{ __('products::app.purchase_price') }}</label>
                        <input type="number" step="0.01" name="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" id="purchase_price"
                            placeholder="{{ __('products::app.purchase_price') }}"
                            value="{{ old('purchase_price', $model->purchase_price ?? '') }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="quantity" class="form-label">{{ __('products::app.quantity') }}</label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                            placeholder="{{ __('products::app.quantity') }}"
                            value="{{ old('quantity', $model->quantity ?? '') }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="expiration_date" class="form-label">{{ __('products::app.expiration_date') }}</label>
                        <input type="date" name="expiration_date" class="form-control @error('expiration_date') is-invalid @enderror dob-picker" id="expiration_date"
                            placeholder="{{ 'YYYY-MM-DD' }}">
                    </div>
                    @if ($edit)
                        <div class="mb-3 col-md-6">
                            <label for="batch_number" class="form-label">{{ __('products::app.batch_number') }}</label>
                            <input type="text" name="batch_number" class="form-control" id="batch_number"
                                placeholder="{{ __('products::app.batch_number') }}"
                                value="{{ old('batch_number', $model->batch_number ?? '') }}" disabled>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    </form>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#expiration_date").flatpickr({
                defaultDate: "{{ old('expiration_date', $model->expiration_date ?? '') }}"
            });
        });
    </script>
@endsection
