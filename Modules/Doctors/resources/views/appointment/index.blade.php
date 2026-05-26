@extends('layouts/contentNavbarLayout')

@section('title', __('doctors::app.appointment'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span class="text-muted fw-light">@lang('app.dashboard') /</span> @lang('doctors::app.appointments')
    </div>
  </h4>

  <!-- Bootstrap Table with Header - Light -->
  <div class="card">
    <div class="card-header border-bottom">
      <x-table.filters :filters="[
        [
          'id' => 'doctor_filter',
          'name' => 'doctor_filter',
          'label' => 'doctors::app.doctor',
          'options' => \Modules\Doctors\Models\Doctor::all()->pluck('fullname', 'id')
        ],
        [
          'id' => 'user_filter',
          'name' => 'user_filter',
          'label' => 'doctors::app.user',
          'options' => \App\Models\User::all()->pluck('fullname', 'id')
        ],
        [
          'id' => 'status_filter',
          'name' => 'status_filter',
          'label' => 'doctors::app.status',
          'options' => \Modules\Doctors\Constants\Appointment\Status::all(true)
        ]
      ]" />
    </div>
    <div class="card-datatable table-responsive">
      <div class="dataTables_wrapper dt-bootstrap5 no-footer">
        <div class="row mx-2">
          <div class="col-md-2">
            <x-table.custom-datatable-length />
          </div>
          <div class="col-md-10">
            <div
              class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
              <x-table.custom-datatable-search />
              <div class="dt-buttons btn-group flex-wrap">
                <!-- Custom buttons can be added here if needed -->
                @permission(\App\Support\Enum\PermissionNames::MANAGE_APPOINTMENTS)
                <a href="{{ route('appointments.create') }}" class="text-white text-decoration-none">
                  <button type="button" class="btn btn-primary" style="float: {{app()->getLocale() == 'ar' ? 'left' : 'right'}}">
                    <span class="tf-icons bx bx-plus"></span> @lang('doctors::app.add-appointment')
                  </button>
                </a>
                @endpermission
              </div>
            </div>
          </div>
        </div>
        <x-table.datatable :columns="$columns" table-id="laravel_datatable" translation-prefix="doctors::app" />
        <x-table.custom-datatable-pagination table-id="laravel_datatable" />
      </div>
    </div>
  </div>
  <!-- Bootstrap Table with Header - Light -->

  <!-- Modals for Actions -->
  @php
    $label = __('doctors::app.appointment-time');
    $inputs = <<<HTML
        <input type="hidden" name="id" value="">
        <input type="hidden" name="status" value="confirmed">
        <label for="time" class="form-label">{$label}</label>
        <input type="time" class="form-control" name="time" id="time" required>
    HTML;
  @endphp

  <x-modal.confirmation
    id="accept-modal"
    title="{{ __('app.accept') }}"
    action="{{ route('appointments.update', ':id') }}"
    method="PATCH"
    :inputs="$inputs"
    theme="success"
  />
  <x-modal.confirmation
    id="cancel-modal"
    title="{{ __('app.cancel') }}"
    action="{{ route('appointments.update', ':id') }}"
    method="PATCH"
    inputs='
    <input type="hidden" name="id" value="">
    <input type="hidden" name="status" value="cancelled">
  '
    theme="danger"
  />
  <x-modal.confirmation
    id="complete-modal"
    title="{{ __('app.complete') }}"
    action="{{ route('appointments.update', ':id') }}"
    method="PATCH"
    inputs='
    <input type="hidden" name="id" value="">
    <input type="hidden" name="status" value="completed">
  '
    theme="success"
  />

  <x-modal.confirmation
    id="delete-modal"
    title="{{ __('app.delete') }}"
    action="{{ route('appointments.destroy', ':id') }}"
    method="DELETE"
    inputs='
    <input type="hidden" name="id" value="">
  '
    theme="danger"
  />

@endsection
@section('scripts')
  <script>
    $(document).ready(function() {
      let filters = {
        doctor_filter: $('#doctor_filter').val(),
        user_filter: $('#user_filter').val(),
        status_filter: $('#status_filter').val()
      };

      let table = initializeDataTable(
        "{{ route('appointments.index') }}",
        @json($columns),
        filters
      );

      // Reload DataTable when the filter value changes
      $('.filter-input').on('change', function() {
        let filterName = $(this).attr('id');
        filters[filterName] = $(this).val();
        table.ajax.reload();
      });

      $(document).on('click', '[data-bs-target="#accept-modal"]', function() {
        const id = $(this).data('id');
        const $form = $('#accept-modal form');
        $form.data('action') ?? $form.data('action', $form.attr('action')); // Store the original action URL
        $form.attr('action', $form.data('action').replace(':id', id));
        $('#accept-modal').find('input[name="id"]').val(id);
      });

      $(document).on('click', '[data-bs-target="#cancel-modal"]', function() {
        const id = $(this).data('id');
        const $form = $('#cancel-modal form');
        $form.data('action') ?? $form.data('action', $form.attr('action')); // Store the original action URL
        $form.attr('action', $form.data('action').replace(':id', id));
        $('#cancel-modal').find('input[name="id"]').val(id);
      });

      $(document).on('click', '[data-bs-target="#delete-modal"]', function () {
        const id = $(this).data('id');
        const $form = $('#delete-modal form');
        $form.data('action') ?? $form.data('action', $form.attr('action')); // Store the original action URL
        $form.attr('action', $form.data('action').replace(':id', id));
        $form.find('input[name="id"]').val(id);
      });

      $(document).on('click', '[data-bs-target="#complete-modal"]', function() {
        const id = $(this).data('id');
        const $form = $('#complete-modal form');
        $form.data('action') ?? $form.data('action', $form.attr('action')); // Store the original action URL
        $form.attr('action', $form.data('action').replace(':id', id));
        $('#complete-modal').find('input[name="id"]').val(id);
      });
    });
  </script>
@endsection
