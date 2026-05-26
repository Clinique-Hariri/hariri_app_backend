@extends('layouts/contentNavbarLayout')

@section('title', __('doctors::app.doctor'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span class="text-muted fw-light">@lang('app.dashboard') /</span> @lang('doctors::app.doctors')
    </div>

  </h4>

  <!-- Bootstrap Table with Header - Light -->
  <div class="card">
    <div class="card-header border-bottom">
      <x-table.filters :filters="[
        [
          'id' => 'department_filter',
          'name' => 'department_filter',
          'label' => 'doctors::app.department',
          'options' => \Modules\Departments\Models\Department::pluck('name', 'id')
        ],
        [
          'id' => 'speciality_filter',
          'name' => 'speciality_filter',
          'label' => 'doctors::app.speciality',
          'options' => \Modules\Doctors\Models\Speciality::pluck('name', 'id')
        ],
        [
          'id' => 'status_filter',
          'name' => 'status_filter',
          'label' => 'doctors::app.status',
          'options' => \Modules\Doctors\Constants\Status::all(true)
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
                @permission(\App\Support\Enum\PermissionNames::MANAGE_DOCTORS)
                <a href="{{ route('doctors.create') }}" class="text-white text-decoration-none">
                  <button type="button" class="btn btn-primary" style="float: {{app()->getLocale() == 'ar' ? 'left' : 'right'}}">
                    <span class="tf-icons bx bx-plus"></span> @lang('doctors::app.add-doctor')
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

  <x-modal.confirmation
    id="delete-modal"
    title="{{ __('app.delete') }}"
    action="{{ route('doctors.destroy', ':id') }}"
    method="DELETE"
    inputs='<input type="hidden" name="id" value="">'
    theme="danger"
  />

@endsection
@section('scripts')
  <script>
    $(document).ready(function () {
      let filters = {
        department_filter: $('#department_filter').val(),
        speciality_filter: $('#speciality_filter').val(),
        status_filter: $('#status_filter').val()
      };

      let table = initializeDataTable(
        "{{ route('doctors.index') }}",
        @json($columns),
        filters
      );

      // Reload DataTable when the filter value changes
      $('.filter-input').on('change', function () {
        let filterName = $(this).attr('id');
        filters[filterName] = $(this).val();
        table.ajax.reload();
      });

      $(document).on('click', '[data-bs-target="#delete-modal"]', function () {
        const id = $(this).data('id');
        const $form = $('#delete-modal form');
        $form.data('action') ?? $form.data('action', $form.attr('action')); // Store the original action URL
        $form.attr('action', $form.data('action').replace(':id', id));
        $form.find('input[name="id"]').val(id);
      });
    });
  </script>
@endsection
