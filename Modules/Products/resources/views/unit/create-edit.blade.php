@extends('layouts/contentNavbarLayout')

@section('title', __('products::app.unit'))

@section('content')
    <!-- Header Actions -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1 mt-3">{{ __($edit ? 'app.edit' : 'app.add') }} {{ __('products::app.unit') }}</h4>
            <p class="text-muted">{{ __('products::app.unit_info') }}</p>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-3">
            @permission(\App\Support\Enum\PermissionNames::MANAGE_PRODUCTS)
                <a href="{{ route('units.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
                </a>
            @endpermission
            <button type="submit" form="unitForm" class="btn btn-primary">
                <i class="bx bx-save me-1"></i>{{ __('app.send') }}
            </button>
        </div>
    </div>

    @if ($edit)
        <form id="unitForm" action="{{ route('units.update', $model->id) }}" method="POST">
            @csrf
            @method('PATCH')
        @else
            <form id="unitForm" action="{{ route('units.store') }}" method="POST">
                @csrf
    @endif

    <!-- Unit Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('products::app.basic_details') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Unit Name (Multilingual) -->
                    <div class="mb-3">
                        <label class="form-label">{{ __('app.name') }}</label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="name[fr]"
                                    class="form-control @error('name.fr') is-invalid @enderror"
                                    placeholder="@lang('app.french')"
                                    value="{{ old('name.fr', $edit ? $model->getTranslation('name', 'fr') : '') }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="name[en]"
                                    class="form-control @error('name.en') is-invalid @enderror"
                                    placeholder="@lang('app.english')"
                                    value="{{ old('name.en', $edit ? $model->getTranslation('name', 'en') : '') }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="name[ar]"
                                    class="form-control @error('name.ar') is-invalid @enderror"
                                    placeholder="@lang('app.arabic')"
                                    value="{{ old('name.ar', $edit ? $model->getTranslation('name', 'ar') : '') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </form>
@endsection
