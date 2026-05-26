@extends('layouts.contentNavbarLayout')

@section('title', __('orders::app.orders'))

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />s
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
@endsection

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1 mt-3">{{ __('orders::app.orders') }}</h4>
        </div>
    </div>

    <!-- Order List Widget -->

    <div class="card mb-4">
        <div class="card-widget-separator-wrapper">
            <div class="card-body card-widget-separator">
                <div class="row gy-4 gy-sm-1">
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                            <div>
                                <h3 class="mb-2">{{$counts['pending']}}</h3>
                                <p class="mb-0">@lang('orders::app.pending_orders')</p>
                            </div>
                            <div class="avatar me-sm-4">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="bx bx-time bx-sm"></i>
                                </span>
                            </div>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-4">
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                            <div>
                                <h3 class="mb-2">{{$counts['accepted']}}</h3>
                                <p class="mb-0">@lang('orders::app.accepted_orders')</p>
                            </div>
                            <div class="avatar me-sm-4">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="bx bx-check bx-sm"></i>
                                </span>
                            </div>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-4">
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                            <div>
                                <h3 class="mb-2">{{$counts['canceled']}}</h3>
                                <p class="mb-0">@lang('orders::app.canceled_orders')</p>
                            </div>
                            <div class="avatar me-sm-4">
                                <span class="avatar-initial rounded bg-label-danger">
                                    <i class="bx bx-x bx-sm"></i>
                                </span>
                            </div>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-4">
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                            <div>
                                <h3 class="mb-2">{{$counts['ongoing']}}</h3>
                                <p class="mb-0">@lang('orders::app.ongoing_orders')</p>
                            </div>
                            <div class="avatar me-sm-4">
                                <span class="avatar-initial rounded bg-label-blue">
                                    <i class="bx bx-cycling bx-sm"></i>
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
                    'id' => 'driver_filter',
                    'name' => 'driver_filter',
                    'label' => 'orders::app.driver',
                    'width' => 'col-md-3',
                    'options' => $drivers,
                ],
                [
                    'id' => 'status_filter',
                    'name' => 'status_filter',
                    'label' => 'orders::app.status',
                    'width' => 'col-md-3',
                    'options' => \Modules\Orders\Constants\OrderStatus::all(true),
                ],
                [
                    'id' => 'type_filter',
                    'name' => 'type_filter',
                    'label' => 'orders::app.type',
                    'width' => 'col-md-3',
                    'options' => \Modules\Orders\Constants\OrderType::all(true),
                ],
                [
                    'id' => 'date_filter',
                    'name' => 'date_filter',
                    'width' => 'col-md-3',
                    'label' => 'orders::app.order_date',
                    'type' => 'date',
                ],

            ]"/>

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
                <a href="{{ route('orders.create') }}" class="text-white text-decoration-none">
                  <button type="button" class="btn btn-primary">
                    <span class="tf-icons bx bx-plus"></span> @lang('orders::app.create_new_order')
                  </button>
                </a>
                @endpermission
              </div>
                        </div>
                    </div>
                </div>
                <x-table.datatable :columns="$columns" table-id="laravel_datatable" translation-prefix="orders::app" />
                <x-table.custom-datatable-pagination table-id="laravel_datatable" />
            </div>
        </div>
    </div>

    @php
        $driverOptions = '';
        foreach ($drivers as $key => $value) {
            $driverOptions .= '<option value="' . $key . '">' . $value . '</option>';
        }

    $driverInputs = '
      <input type="hidden" name="id" value="">
      <input type="hidden" name="status" value="">
      <div class="mb-3" id="driver-selection" style="display: none;">
        <label for="driver_id" class="form-label">' . __('orders::app.select_driver') . '</label>
        <select class="form-select" id="driver_id" name="driver_id" data-placeholder="' . __('app.select') . '">
          <option value="">' . __('orders::app.select_driver') . '</option>
          ' . $driverOptions . '
        </select>
      </div>
    ';

    @endphp

    <!-- Single Dynamic Modal for Order Status Updates -->
    <x-modal.confirmation id="updateStatusModal" title="{{ __('orders::app.update_order_status') }}"
        action="{{ route('orders.update-status') }}" method="POST"
        inputs="{!! $driverInputs !!}"
        theme="primary" confirmationTitle="{{ __('orders::app.confirm_status_change') }}"
        confirmationText="{{ __('orders::app.update_order_status') }}"
        checkboxLabel="{{ __('orders::app.confirm_status_change_checkbox') }}" submitLabel="{{ __('app.submit') }}"
        cancelLabel="{{ __('app.cancel') }}" requireConfirmation="true" />

    <x-modal.confirmation id="delete-modal" title="{{ __('app.delete') }}" action="{{ route('orders.destroy', ':id') }}"
        method="DELETE" inputs='
    <input type="hidden" name="id" value="">
  ' theme="danger" />
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('.select2').select2({
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            $("#date_filter").flatpickr();
            initializeDateFilter('date_filter');

            let filters = {
                driver_filter: $('#driver_filter').val(),
                status_filter: $('#status_filter').val(),
                type_filter: $('#type_filter').val(),
                date_filter: $('#date_filter').val()
            };

            let table = initializeDataTable(
                "{{ route('orders.index') }}",
                @json($columns),
                filters
            );

            $('.filter-input').on('change', function() {
                let filterName = $(this).attr('id');
                filters[filterName] = $(this).val();
                table.ajax.reload();
            });

            const statusConfig = {
                pending: {
                    title: "{{ __('orders::app.mark_order_pending') }}",
                    notice: "{{ __('orders::app.pending_status_notice') }}",
                    theme: "warning"
                },
                accepted: {
                    title: "{{ __('orders::app.accept_order') }}",
                    notice: "{{ __('orders::app.accepted_status_notice') }}",
                    theme: "teal"
                },
                ongoing: {
                    title: "{{ __('orders::app.start_order_delivery') }}",
                    notice: "{{ __('orders::app.ongoing_status_notice') }}",
                    theme: "blue",
                    requiresDriver: true
                },
                delivered: {
                    title: "{{ __('orders::app.mark_order_delivered') }}",
                    notice: "{{ __('orders::app.delivered_status_notice') }}",
                    theme: "success"
                },
                canceled: {
                    title: "{{ __('orders::app.cancel_order') }}",
                    notice: "{{ __('orders::app.canceled_status_notice') }}",
                    theme: "danger"
                }
            };

            $(document).on('click', '[data-bs-target="#updateStatusModal"]', function() {
                const orderId = $(this).data('id');
                const status = $(this).data('status');
                const config = statusConfig[status];
                const $modal = $('#updateStatusModal');
                const $form = $modal.find('form');
                const $alert = $modal.find('.alert');
                const $submitBtn = $modal.find('button[type="submit"]');
                const $driverSelection = $modal.find('#driver-selection');
                const $driverSelect = $modal.find('#driver_id');
                const $modalTitle = $modal.find('.modal-title');
                const $confirmationText = $alert.find('p');

                $modal.find('input[name="confirmed"]').prop('checked', false);
                $modalTitle.text(config.title);

                $form.data('action') ?? $form.data('action', $form.attr('action'));
                $form[0].reset();
                $form.find('input[name="id"]').val(orderId);
                $form.find('input[name="status"]').val(status);

                $alert.removeClass('alert-warning alert-info alert-primary alert-success alert-danger')
                    .addClass(`alert-${config.theme}`);

                $confirmationText.text(config.notice);

                $submitBtn.removeClass('btn-warning btn-info btn-primary btn-success btn-danger')
                    .addClass(`btn-${config.theme}`);

                if (config.requiresDriver) {
                    $driverSelection.show();
                    $driverSelect.prop('required', true);
                } else {
                    $driverSelection.hide();
                    $driverSelect.prop('required', false);
                }
            });

            $('#updateStatusModal').on('hidden.bs.modal', function() {
                const $form = $(this).find('form');
                $form[0].reset();
                $(this).find('#driver-selection').hide();
                $(this).find('#driver_id').prop('required', false);
            });

            $(document).on('click', '[data-bs-target="#delete-modal"]', function() {
                const id = $(this).data('id');
                const $form = $('#delete-modal form');
                $form.data('action') ?? $form.data('action', $form.attr(
                'action')); // Store the original action URL
                $form.find('input[name="id"]').val(id);
            });
        });
    </script>
@endsection
