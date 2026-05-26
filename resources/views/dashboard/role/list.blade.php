@extends('layouts/contentNavbarLayout')

@section('title', __('app.role'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span class="text-muted fw-light">@lang('app.dashboard') /</span> @lang('app.role')
    </div>
    <div class="col-md-auto">
    </div>
  </h4>

  <!-- Bootstrap Table with Header - Light -->
  <div class="card">
    <h5 class="card-header">@lang('app.roles')</h5>
    <div class="table-responsive text-nowrap">
      <table id="laravel_datatable" class="table">
        <thead class="table-light">
          <tr>
            <th>#</th>
            @foreach ($columns as $column)
              <th>{{ __('app.'.$column) }}</th>
              @endforeach
          </tr>
        </thead>
      </table>
    </div>
  </div>
  <!-- Bootstrap Table with Header - Light -->
@endsection
@section('page-script')
  <script>
    $(document).ready(function() {
      initializeDataTable("{{ route('roles.index') }}", @json($columns));
    });
  </script>
@endsection
