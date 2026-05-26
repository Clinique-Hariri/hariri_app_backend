@extends('layouts/contentNavbarLayout')

@section('title', __('assurance::app.patient'))

@section('content')
{{--  <h4 class="fw-bold py-3 mb-3 row justify-content-between">--}}
{{--    <div class="col-md-auto">--}}
{{--      <span class="text-muted fw-light">@lang('app.dashboard')/</span> @lang('assurance::app.patients')--}}
{{--    </div>--}}
{{--  </h4>--}}

{{-- to display the receipt --}}
<script>
  @if(session('open_url'))
  window.open("{!! session('open_url') !!}", "_blank");
  @endif
</script>

<div class="app-academy">
  <div class="card p-0 mb-4">
    <div class="card-body d-flex flex-column flex-md-row justify-content-between p-0 pt-4">
      <div class="app-academy-md-25 card-body p-0 ps-3">
        <img src="{{ asset('assets/img/illustrations/qr-code-scan.png') }}" class="img-fluid scaleX-n1-rtl" alt="Bulb in hand" style="max-height: 250px; width: auto;" />
      </div>
      <div class="app-academy-md-50 card-body d-flex align-items-md-center flex-column text-md-center">
        <h3 class="card-title mb-4 lh-sm px-md-5 text-center">
          @lang('assurance::app.welcome_to_your_medical_visit_system')
        </h3>
        <p class="mb-4">
          @lang('assurance::app.please_scan_qr_code_to_start_the_medical_visit_process')
        </p>
        <div class="w-100 mt-3">
          <div class="d-flex w-100">
            <input
              id="qr_code_input"
              type="search"
              placeholder="@lang('assurance::app.scan_or_enter_qr_code')"
              class="form-control form-control-lg me-2 fs-5 py-3"
            />
            <button
              id="qr_code_search_button"
              type="submit"
              class="btn btn-primary btn-lg px-4 py-3 fs-5 d-flex align-items-center justify-content-center"
            >
              <i class="bx bx-search fs-4"></i>
            </button>
          </div>
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
          'id' => 'status_filter',
          'name' => 'status_filter',
          'label' => 'assurance::app.status',
          'options' => \App\Constants\Statuses\UserStatus::all(true)
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
              </div>
            </div>
          </div>
        </div>
        <x-table.datatable :columns="$columns" table-id="laravel_datatable" translation-prefix="assurance::app" />
        <x-table.custom-datatable-pagination table-id="laravel_datatable" />
      </div>
    </div>
  </div>
  <!--/ Bootstrap Table with Header - Light -->

@endsection
@section('page-script')
  <script>
    $(document).ready(function() {
      let filters = {
        user_status_filter: $('#status_filter').val()
      };

      let table = initializeDataTable(
        "{{ route('patients.index') }}",
        @json($columns),
        filters
      );

      // Reload DataTable when the filter value changes
      $('.filter-input').on('change', function() {
        let filterName = $(this).attr('id');
        filters[filterName] = $(this).val();
        table.ajax.reload();
      });

      function searchClientByQRCode() {
        const qrCode = $('#qr_code_input').val().trim();

        if (!qrCode) {
          alert('Please enter or scan a QR code.');
          return;
        }

        $.ajax({
          url: `/admin/patients/get-family-member-with-qr-code/${encodeURIComponent(qrCode)}`,
          method: 'GET',
          success: function (response) {
            const userId = response.user_id;
            const familyMemberId = response.id;

            window.location.href = `/admin/patients/${userId}/examinations/create?family_member_id=${familyMemberId}`;
          },
          error: function (xhr) {
            if (xhr.status === 404) {
              alert("@lang('app.not_found')");
            } else if (xhr.status === 403 || xhr.status === 302) {
              alert('Unauthorized.');
              window.location.href = '/unauthorized';
            } else {
              alert("@lang('app.error')");
              console.error(xhr);
            }
          }
        });
      }

      // Handle button click
      $('#qr_code_search_button').on('click', function (e) {
        e.preventDefault();
        searchClientByQRCode();
      });

      // Handle Enter key on input
      $('#qr_code_input').on('keypress', function (e) {
        if (e.which === 13) {
          e.preventDefault();
          searchClientByQRCode();
        }
      });
    });
  </script>
@endsection
