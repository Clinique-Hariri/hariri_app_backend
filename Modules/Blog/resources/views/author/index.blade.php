@extends('layouts/contentNavbarLayout')

@section('title', __('blog::app.authors'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span class="text-muted fw-light">@lang('app.dashboard') /</span> @lang('blog::app.authors')
    </div>
    <div class="col-md-auto">

    </div>
  </h4>

  <!-- Bootstrap Table with Header - Light -->
  <div class="card">
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
                @permission(\App\Support\Enum\PermissionNames::MANAGE_BLOG_AUTHORS)
                <a href="{{ route('blog.authors.create') }}" class="text-white text-decoration-none">
                  <button type="button" class="btn btn-primary" style="float: {{app()->getLocale() == 'ar' ? 'left' : 'right'}}">
                    <span class="tf-icons bx bx-plus"></span> @lang('blog::app.create') @lang('blog::app.author')
                  </button>
                </a>
                @endpermission
              </div>
            </div>
          </div>
        </div>
        <x-table.datatable :columns="$columns" table-id="laravel_datatable" translation-prefix="blog::app" />
        <x-table.custom-datatable-pagination table-id="laravel_datatable" />
      </div>
    </div>
  </div>
  <!-- Bootstrap Table with Header - Light -->

  <x-modal.confirmation
    id="delete-modal"
    title="{{ __('app.delete') }}"
    action="{{ route('blog.authors.destroy', ':id') }}"
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
      let filters = {};

      let table = initializeDataTable(
        "{{ route('blog.authors.index') }}",
        @json($columns),
        filters
      );

      $(document).on('click', '[data-bs-target="#delete-modal"]', function () {
        const id = $(this).data('id');
        const $form = $('#delete-modal form');
        $form.data('action') ?? $form.data('action', $form.attr('action'));
        $form.attr('action', $form.data('action').replace(':id', id));
        $form.find('input[name="id"]').val(id);
      });
    });
  </script>
@endsection
