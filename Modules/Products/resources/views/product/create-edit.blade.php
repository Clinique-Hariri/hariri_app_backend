@extends('layouts/contentNavbarLayout')

@section('title', __('products::app.product'))

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('content')

    <!-- Header Actions -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1 mt-3">{{ __($edit ? 'app.edit' : 'app.add') }} {{ __('products::app.product') }}</h4>
            <p class="text-muted">{{ __('products::app.product_info') }}</p>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-3">
            @permission(\App\Support\Enum\PermissionNames::MANAGE_PRODUCTS)
                <a href="{{ route('products.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
                </a>
            @endpermission
            <button type="submit" form="form" class="btn btn-primary">
                <i class="bx bx-save me-1"></i>{{ __('app.send') }}
            </button>
        </div>
    </div>

    @if ($edit)
        <form id="form" action="{{ route('products.update', $model->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PATCH')
        @else
            <form id="form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
    @endif

    <!-- First Row: Product Information and Image -->
    <div class="row mb-4">
        <!-- Product Information -->
        <div class="col-12 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('products::app.basic_details') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Product Name (Single field) -->
                    <div class="mb-3">
                        <label class="form-label" for="name">{{ __('products::app.commercial_name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" placeholder="{{ __('products::app.commercial_name') }}"
                            value="{{ old('name', $edit ? $model->name : '') }}">
                    </div>

                    <!-- SKU and Barcode Row -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"
                                for="scientific_name">{{ __('products::app.scientific_name') }}</label>
                            <input type="text" class="form-control @error('scientific_name') is-invalid @enderror"
                                id="scientific_name" name="scientific_name"
                                placeholder="{{ __('products::app.scientific_name') }}"
                                value="{{ old('scientific_name', $model->scientific_name ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="barcode">{{ __('products::app.barcode') }}</label>
                            <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="barcode"
                                name="barcode" placeholder="{{ __('products::app.barcode') }}"
                                value="{{ old('barcode', $model->barcode ?? '') }}">
                        </div>
                    </div>

                    <!-- Description (Single field) -->
                    <div class="mb-3">
                        <label class="form-label" for="description">{{ __('products::app.description') }}</label>
                        <div class="form-control @error('description') is-invalid @enderror p-0">
                            <div class="comment-editor border-0 pb-4" id="description-editor">
                                {!! old('description', $edit ? $model->description : '') !!}
                            </div>
                            <input type="hidden" name="description" id="description">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('products::app.advanced_settings') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Category -->
                    <!-- Category and Status -->
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label mb-1" for="category_id">{{ __('products::app.category') }}</label>
                            <select id="category_id" name="category_id" class="select2 form-select @error('category_id') is-invalid @enderror"
                                data-placeholder="{{ __('products::app.select_category') }}">
                                <option value="">{{ __('products::app.select_category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $model->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="status">@lang('products::app.status')</label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror select2"
                                id="status">
                                @foreach (\Modules\Products\Constants\Status::all(true) as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ old('status', $model->status ?? '') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Unit and Unit Count -->
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label mb-1" for="unit_id">{{ __('products::app.unit') }}</label>
                            <select id="unit_id" name="unit_id" class="form-select @error('unit_id') is-invalid @enderror select2"
                                data-placeholder="{{ __('products::app.select_unit') }}">
                                <option value="">{{ __('products::app.select_unit') }}</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ old('unit_id', $model->unit_id ?? '') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label mb-1" for="unit_count">{{ __('products::app.unit_count') }}</label>
                            <input type="number" class="form-control @error('unit_count') is-invalid @enderror"
                                id="unit_count" name="unit_count" placeholder="{{ __('products::app.unit_count') }}"
                                value="{{ old('unit_count', $model->unit_count ?? 1) }}">
                        </div>
                    </div>

                    <!-- Alternative Products -->
                    <div class="mb-3 col select2-primary">
                        <label class="form-label mb-1"
                            for="alternative_products">{{ __('products::app.alternative_products') }}</label>
                        <select id="alternative_products" name="alternative_products[]" class="select2 form-select @error('alternative_products') is-invalid @enderror"
                            multiple data-placeholder="{{ __('products::app.select_alternatives') }}">
                            @foreach ($products as $product)
                                @if (!$edit || $product->id != $model->id)
                                    <option value="{{ $product->id }}"
                                        {{ (old('alternative_products') && in_array($product->id, old('alternative_products'))) ||
                                        ($edit && $model->alternatives->contains('alternate_product_id', $product->id))
                                            ? 'selected'
                                            : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Product Options -->
                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        <span class="mb-0 h6">{{ __('products::app.is_compensable') }}</span>
                        <div class="w-25 d-flex justify-content-end">
                            <input type="hidden" name="is_compensable" value="0">
                            <label class="switch switch-primary switch-sm me-4 pe-2">
                                <input type="checkbox" class="switch-input" name="is_compensable" value="1"
                                    {{ old('is_compensable', $model->is_compensable ?? false) ? 'checked' : '' }}>
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <span class="switch-off"></span>
                                    </span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3">
                        <span class="mb-0 h6">{{ __('products::app.with_prescription') }}</span>
                        <div class="w-25 d-flex justify-content-end">
                            <input type="hidden" name="with_prescription" value="0">
                            <label class="switch switch-primary switch-sm me-4 pe-2">
                                <input type="checkbox" class="switch-input" name="with_prescription" value="1"
                                    {{ old('with_prescription', $model->with_prescription ?? false) ? 'checked' : '' }}>
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <span class="switch-off"></span>
                                    </span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Media -->
        <div class="col-12 col-lg-4">

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('products::app.pricing_and_stock') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Base Price -->
                    <div class="mb-3">
                        <label class="form-label" for="price">{{ __('products::app.price') }}</label>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                            id="price" name="price" placeholder="{{ __('products::app.price') }}"
                            value="{{ old('price', $model->price ?? '') }}">
                    </div>

                    <!-- Discounted Price -->
                    <div class="mb-3">
                        <label class="form-label"
                            for="discounted_price">{{ __('products::app.discounted_price') }}</label>
                        <input type="number" step="0.01"
                            class="form-control @error('discounted_price') is-invalid @enderror" id="discounted_price"
                            name="discounted_price" placeholder="{{ __('products::app.discounted_price') }}"
                            value="{{ old('discounted_price', $model->final_price ?? '') }}"
                            {{ $edit ? 'disabled' : '' }}>
                    </div>

                    <!-- Purchase Price -->
                    <div class="mb-3">
                        <label class="form-label" for="purchase_price">{{ __('products::app.purchase_price') }}</label>
                        <input type="number" step="0.01"
                            class="form-control @error('purchase_price') is-invalid @enderror" id="purchase_price"
                            name="purchase_price" placeholder="{{ __('products::app.purchase_price') }}"
                            value="{{ old('purchase_price', $model->purchase_price ?? '') }}"
                            {{ $edit ? 'disabled' : '' }}>
                    </div>

                    <!-- Stock Quantity -->
                    <div class="mb-3">
                        <label class="form-label" for="quantity">{{ __('products::app.quantity') }}</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                            name="quantity" placeholder="{{ __('products::app.quantity') }}"
                            value="{{ old('quantity', $model->stock_quantity ?? '') }}" {{ $edit ? 'disabled' : '' }}>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 card-title">{{ __('products::app.image') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center gap-4 mb-4">
                        @if ($edit && $model->getFirstMediaUrl('image'))
                            <img src="{{ $model->getFirstMediaUrl('image') }}" alt="Product Image"
                                class="d-block rounded" height="200" width="200" id="uploadedAvatar">
                        @else
                            <img src="{{ asset('assets/img/avatars/9.png') }}" alt="Placeholder" class="d-block rounded"
                                height="200" width="200" id="uploadedAvatar">
                        @endif
                        <div class="button-wrapper text-center">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">{{ __('app.new_image') }}</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" name="image" class="account-file-input" hidden
                                    accept="image/png, image/jpeg, image/gif" />
                            </label>
                            <button type="button" class="btn btn-label-secondary account-image-reset mb-4">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">{{ __('app.reset') }}</span>
                            </button>
                            <p class="text-muted mb-0">{{ __('app.allowed_formats') }}: JPG, PNG, GIF.
                                {{ __('app.max_size') }}: 2MB</p>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    </form>

@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            createBasicTextEditor('#description-editor');
            const $desc = $('#description');
            $desc.val(quill.root.innerHTML);
            quill.on('text-change', () => $desc.val(quill.root.innerHTML));
            $('form').on('submit', () => $desc.val(quill.root.innerHTML));

            // Update/reset product image
            let accountUserImage = document.getElementById('uploadedAvatar');
            const fileInput = document.querySelector('.account-file-input'),
                resetFileInput = document.querySelector('.account-image-reset');

            if (accountUserImage) {
                const resetImage = accountUserImage.src;
                fileInput.onchange = () => {
                    if (fileInput.files[0]) {
                        accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
                    }
                };
                resetFileInput.onclick = () => {
                    fileInput.value = '';
                    accountUserImage.src = resetImage;
                };
            }
        });
    </script>
@endsection
