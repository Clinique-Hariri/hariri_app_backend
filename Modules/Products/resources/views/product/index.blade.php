@extends('layouts/contentNavbarLayout')

@section('title', __('products::app.product'))

@section('content')


<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
  <div class="d-flex flex-column justify-content-center">
    <h4 class="mb-1 mt-3">{{ __('products::app.products') }}</h4>
  </div>
</div>

<div class="card mb-4">
  <div class="card-widget-separator-wrapper">
      <div class="card-body card-widget-separator">
          <div class="row gy-4 gy-sm-1">
              <div class="col-sm-6 col-lg-3">
                  <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                      <div>
                          <h3 class="mb-2">{{$counts['total']}}</h3>
                          <p class="mb-0">@lang('products::app.total_products')</p>
                      </div>
                      <div class="avatar me-sm-4">
                          <span class="avatar-initial rounded bg-label-primary">
                              <i class="bx bx-capsule bx-sm"></i>
                          </span>
                      </div>
                  </div>
                  <hr class="d-none d-sm-block d-lg-none me-4">
              </div>
              <div class="col-sm-6 col-lg-3">
                  <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                      <div>
                          <h3 class="mb-2">{{$counts['quantity']}}</h3>
                          <p class="mb-0">@lang('products::app.total_quantity')</p>
                      </div>
                      <div class="avatar me-sm-4">
                          <span class="avatar-initial rounded bg-label-info">
                              <i class="bx bx-package bx-sm"></i>
                          </span>
                      </div>
                  </div>
                  <hr class="d-none d-sm-block d-lg-none me-4">
              </div>
              <div class="col-sm-6 col-lg-3">
                  <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                      <div>
                          <h3 class="mb-2">{{$counts['in_transit']}}</h3>
                          <p class="mb-0">@lang('products::app.total_ordered')</p>
                      </div>
                      <div class="avatar me-sm-4">
                          <span class="avatar-initial rounded bg-label-warning">
                              <i class="bx bx-transfer-alt bx-sm"></i>
                          </span>
                      </div>
                  </div>
                  <hr class="d-none d-sm-block d-lg-none me-4">
              </div>
              <div class="col-sm-6 col-lg-3">
                  <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                      <div>
                          <h3 class="mb-2">{{$counts['discounted']}}</h3>
                          <p class="mb-0">@lang('products::app.total_discounted')</p>
                      </div>
                      <div class="avatar me-sm-4">
                          <span class="avatar-initial rounded bg-label-success">
                              <i class="bx bxs-discount bx-sm"></i>
                          </span>
                      </div>
                  </div>
                  <hr class="d-none d-sm-block d-lg-none me-4">
              </div>
          </div>
      </div>
  </div>
</div>

  <!-- Bootstrap Table with Header - Light -->
  <div class="card">
    <div class="card-header border-bottom">
      <x-table.filters :filters="[
        [
          'id' => 'category_filter',
          'name' => 'category_filter',
          'label' => 'products::app.category',
          'options' => $categories
        ],
        [
          'id' => 'unit_filter',
          'name' => 'unit_filter',
          'label' => 'products::app.unit',
          'options' => $units
        ],
        [
          'id' => 'status_filter',
          'name' => 'status_filter',
          'label' => 'products::app.status',
          'options' => \Modules\Products\Constants\Status::all(true)
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
                @permission(\App\Support\Enum\PermissionNames::MANAGE_PRODUCTS)
                <a href="{{ route('products.create') }}" class="text-white text-decoration-none">
                  <button type="button" class="btn btn-primary">
                    <span class="tf-icons bx bx-plus"></span> @lang('products::app.add_new_product')
                  </button>
                </a>
                @endpermission
              </div>
            </div>
          </div>
        </div>
        <x-table.datatable :columns="$columns" table-id="laravel_datatable" translation-prefix="products::app" />
        <x-table.custom-datatable-pagination table-id="laravel_datatable" />
      </div>
    </div>
  </div>

  <x-modal.confirmation
    id="delete-modal"
    title="{{ __('app.delete') }}"
    action="{{ route('products.destroy', ':id') }}"
    method="DELETE"
    inputs='
    <input type="hidden" name="id" value="">
  '
    theme="danger"
  />

@endsection
@section('scripts')
  <script>
    $(document).ready(function () {
      $('.select2').select2();

      let filters = {
        category_filter: $('#category_filter').val(),
        status_filter: $('#status_filter').val()
      };

      let table = initializeDataTable(
        "{{ route('products.index') }}",
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
