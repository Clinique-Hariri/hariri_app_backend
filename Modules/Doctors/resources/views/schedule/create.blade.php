@extends('layouts/contentNavbarLayout')

@section('title', __('doctors::app.schedule'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span class="text-muted fw-light">{{$doctor->full_name}}/ @lang('doctors::app.schedules')/</span>
      {{__('app.add') . ' ' . __('doctors::app.schedule')}}
    </div>
    <div class="col-md-auto">
      @permission(\App\Support\Enum\PermissionNames::MANAGE_DOCTORS)
      <a href="{{ route('doctors.schedules.index', $doctor->id) }}" class="text-white text-decoration-none">
        <button type="button" class="btn btn-primary" style="float: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
          <span class="tf-icons bx bx-arrow-back"></span> @lang('app.back')
        </button>
      </a>
      @endpermission
    </div>
  </h4>


  <form action="{{ route('doctors.schedules.store', $doctor->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
      <div class="col-xl">
        <div class="card mb-4">
          <div class="row card-body">
            <div class="mb-3 col-md-6">
              <label for="start_time" class="form-label">
                @lang('doctors::app.start_time')
                <span class="text-danger fs-6 ms-1">*</span>
              </label>
              <input type="time" class="form-control" id="start_time" name="start_time"
                     value="{{ old('start_time') }}" required>
            </div>
            <div class="mb-3 col-md-6">
              <label for="end_time" class="form-label">
                @lang('doctors::app.end_time')
                <span class="text-danger fs-6 ms-1">*</span>
              </label>
              <input type="time" class="form-control" id="end_time" name="end_time"
                     value="{{ old('end_time') }}" required>
            </div>
            <div class="mb-3 col-md-6">
              <label for="days_of_week" class="form-label">
                @lang('doctors::app.days_of_week')
                <span class="text-danger fs-6 ms-1">*</span>
              </label>
              <select name="days_of_week[]" id="days_of_week" multiple required>
                @foreach(\App\Support\Enum\WeekDays::lists(true) as $key => $value)
                  <option value="{{ $key }}"
                    {{ in_array($key, old('days_of_week', $model?->days_of_week ?? [])) ? 'selected' : '' }}>
                    {{ $value }}
                  </option>
                @endforeach
              </select>
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
@section('page-style')
  <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endsection
@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
  <script>
    $(document).ready(function () {
      new TomSelect('#days_of_week', {
        plugins: ['remove_button'],
        maxItems: null,
        placeholder: "{{ __('app.select_options') }}",
        create: false
      });
    });
  </script>
@endsection
