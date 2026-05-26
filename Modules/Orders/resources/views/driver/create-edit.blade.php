@extends('layouts.contentNavbarLayout')

@section('title', $edit ? __('orders::app.edit_driver') : __('orders::app.create_driver'))

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('content')
    <!-- Header Actions -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1 mt-3">{{ __($edit ? 'app.edit' : 'app.add') }} {{ __('orders::app.driver') }}</h4>
            <p class="text-muted">{{ __('orders::app.driver_info') }}</p>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-3">
            @permission(\App\Support\Enum\PermissionNames::MANAGE_ORDERS)
                <a href="{{ route('drivers.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
                </a>
            @endpermission
            <button type="submit" form="driverForm" class="btn btn-primary">
                <i class="bx bx-save me-1"></i>{{ __('app.send') }}
            </button>
        </div>
    </div>

    @if ($edit)
        <form id="driverForm" action="{{ route('drivers.update', $model->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PATCH')
        @else
            <form id="driverForm" action="{{ route('drivers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
    @endif

    <!-- First Row: Driver Information and Image -->
    <div class="row mb-4">
        <!-- Driver Information -->
        <div class="col-12 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('orders::app.basic_details') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Driver Name -->
                    <div class="mb-3">
                        <label class="form-label">{{ __('orders::app.fullname') }}</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            placeholder="{{ __('orders::app.fullname') }}"
                            value="{{ old('name', $edit ? $model->name : '') }}">
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3">
                        <label class="form-label">{{ __('orders::app.phone') }}</label>
                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            placeholder="{{ __('orders::app.phone') }}"
                            value="{{ old('phone', $edit ? $model->phone : '') }}"
                            @if (app()->isLocale('ar')) dir="rtl" @endif>
                    </div>

                </div>
            </div>
        </div>

        <!-- Media -->
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 card-title">{{ __('orders::app.image') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center gap-4 mb-4">
                        @if ($edit && $model->getFirstMediaUrl('image'))
                            <img src="{{ $model->getFirstMediaUrl('image') }}" alt="Driver Image" class="d-block rounded"
                                height="200" width="200" id="uploadedAvatar">
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

            // Update/reset driver image
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
