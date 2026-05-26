@extends('layouts/contentNavbarLayout')

@section('title', __('ads::app.ad'))

@section('content')
    <h4 class="fw-bold py-3 mb-3 row justify-content-between">
        <div class="col-md-auto">
            <span class="text-muted fw-light">@lang('ads::app.ads')/</span>
            {{ $edit ? __('app.edit') . ' ' . $model->name : __('app.add') . ' ' . __('ads::app.ad') }}
        </div>
        <div class="col-md-auto">
            @permission(\App\Support\Enum\PermissionNames::MANAGE_ADS)
                <a href="{{ route('ads.index') }}" class="text-white text-decoration-none">
                    <button type="button" class="btn btn-primary" style="float: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
                        <span class="tf-icons bx bx-arrow-back"></span> @lang('app.back')
                    </button>
                </a>
            @endpermission
        </div>
    </h4>

    @if ($edit)
        <form action="{{ route('ads.update', $model->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
        @else
            <form action="{{ route('ads.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
    @endif

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="row card-body">
                    <div class="mb-3 col-md-6">
                        <label for="name" class="form-label">{{ __('ads::app.name') }}</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
                            placeholder="{{ __('ads::app.name') }}" value="{{ old('name', $model->name ?? '') }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="expiration_date" class="form-label">{{ __('ads::app.expiration_date') }}</label>
                        <input type="date" name="expiration_date" class="form-control @error('expiration_date') is-invalid @enderror" id="expiration_date"
                            placeholder="{{ __('ads::app.expiration_date') }}"
                            value="{{ old('expiration_date', $edit ? $model->expiration_date?->format('Y-m-d') : null) }}"
                            required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="type" class="form-label">{{ __('ads::app.type') }}</label>
                        <select name="type" class="form-control @error('type') is-invalid @enderror select2" id="type"
                        data-placeholder="{{ __('app.select_option') }}" required>
                            @foreach (\Modules\Ads\Constants\AdType::all(true) as $key => $value)
                                <option value="{{ $key }}"
                                    {{ old('type', $model->type ?? '') == $key ? 'selected' : '' }}>{{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="link" class="form-label">{{ __('ads::app.link') }}</label>
                        <input type="text" name="link" class="form-control @error('link') is-invalid @enderror" id="link"
                            placeholder="{{ __('ads::app.link') }}" value="{{ old('link', $model->link ?? '') }}"
                            required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="status" class="form-label">{{ __('ads::app.status') }}</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror select2" id="status"
                        data-placeholder="{{ __('app.select_option') }}" required>
                            @foreach (\Modules\Ads\Constants\Status::all(true) as $key => $value)
                                <option value="{{ $key }}"
                                    {{ old('status', $model->status ?? '') == $key ? 'selected' : '' }}>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="user_types" class="form-label">{{ __('ads::app.user_types') }}</label>
                        <select name="user_types[]" class="form-control @error('user_types') is-invalid @enderror select2" id="user_types" multiple
                        data-placeholder="{{ __('app.select_options') }}" required>
                            @foreach (\Modules\Ads\Constants\UserType::all(true) as $key => $value)
                                <option value="{{ $key }}"
                                    {{ in_array($key, old('user_types', $edit ? $model->user_types ?? [] : [])) ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="image" class="form-label">{{ __('ads::app.image') }}</label>
                        <div class="input-group">
                            <input type="file" name="image" class="form-control @error('max_uses') is-invalid @enderror" id="ad_image"
                                placeholder="{{ __('ads::app.image') }}">
                        </div>
                    </div>

                    <div class="form-group" style="text-align: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
                        <button type="submit" class="btn btn-primary">{{ __('app.send') }}</button>
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
        $('.select2').select2({
          placeholder : $(this).data('placeholder')
        })
        function toggleLinkField() {
          const selectedType = $('#type').val();
          if (selectedType === 'none' || selectedType === 'internal') {
            $('#link').prop('disabled', true).val('');
          } else {
            $('#link').prop('disabled', false);
          }
        }

            $('.select2').select2();

            // Initial check
            toggleLinkField();

            // On change
            $('#type').on('change', toggleLinkField);
        });
    </script>
@endsection
