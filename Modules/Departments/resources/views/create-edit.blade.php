@extends('layouts/contentNavbarLayout')

@section('title', __('departments::app.department'))

@section('vendor-style')
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/typography.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/katex.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
@endsection

@section('vendor-script')
  <script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
@endsection

{{--@section('page-script')--}}
{{--  <script src="{{asset('assets/js/forms-editors.js')}}"></script>--}}
{{--@endsection--}}

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span class="text-muted fw-light">@lang('departments::app.departments')/</span>
      {{$edit ? __('app.edit') . ' '. $model->name : __('app.add') .' ' . __('departments::app.department')}}
    </div>
    <div class="col-md-auto">
      @permission(\App\Support\Enum\PermissionNames::MANAGE_DEPARTMENTS)
      <a href="{{ route('departments.index') }}" class="text-white text-decoration-none">
        <button type="button" class="btn btn-primary" style="float: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
          <span class="tf-icons bx bx-arrow-back"></span> @lang('app.back')
        </button>
      </a>
      @endpermission
    </div>
  </h4>

  @if ($edit)
    <form action="{{ route('departments.update', $model->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PATCH')
      @else
        <form action="{{ route('departments.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @endif

          <div class="row">
            <div class="col-xl">
              <div class="card mb-4">
                <div class="row card-body">
                  <div class="mb-3 col-md-12">
                    <label class="form-label">
                      {{ __('app.name') }}
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <div class="row">
                      <div class="col-md-4 mb-3">
                        <div class="input-group">
                          <span class="input-group-text">@lang('app.french')</span>
                          <input type="text"
                                 name="name[fr]"
                                 id="name_fr"
                                 class="form-control"
                                 placeholder="{{ __('app.french') }}"
                                 value="{{ old('name_fr', $edit ? $model->getTranslation('name', 'fr') : '') }}"
                                 required>
                        </div>
                      </div>
                      <div class="col-md-4 mb-3">
                        <div class="input-group">
                          <span class="input-group-text">@lang('app.english')</span>
                          <input type="text"
                                 name="name[en]"
                                 id="name_en"
                                 class="form-control"
                                 placeholder="{{ __('app.english') }}"
                                 value="{{ old('name_en', $edit ? $model->getTranslation('name', 'en') : '') }}"
                                 required>
                        </div>
                      </div>
                      <div class="col-md-4 mb-3">
                        <div class="input-group">
                          <span class="input-group-text">@lang('app.arabic')</span>
                          <input type="text"
                                 name="name[ar]"
                                 id="name_ar"
                                 class="form-control"
                                 placeholder="{{ __('app.arabic') }}"
                                 value="{{ old('name_ar', $edit ? $model->getTranslation('name', 'ar') : '') }}"
                                 required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label for="status" class="form-label">
                      {{ __('departments::app.status') }}
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <select name="status" class="form-select" id="status" required>
                      <option value="">{{ __('app.select_option') }}</option>
                      @foreach(\Modules\Departments\Constants\Status::all(true) as $key => $value)
                        <option
                          value="{{ $key }}" {{ old('status', $model->status ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label for="image" class="form-label">
                      {{ __('departments::app.image') }}
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <div class="input-group">
                      <input type="file" name="image" class="form-control" id="ad_image"
                             placeholder="{{ __('departments::app.image') }}" {{$edit? '' : 'required'}}>
                    </div>
                  </div>
                  <div class="mb-3 col-md">
                    <label for="status" class="form-label">
                      {{ __('departments::app.description') }}
                      <span class="text-danger fs-6 ms-1">*</span>
                    </label>
                    <div id="full-editor">
                      {!! $edit ? $model->description : '' !!}
                    </div>
                    <input type="hidden" name="description" id="description"/>
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
            $(document).ready(function() {
              function toggleLinkField() {
                const selectedType = $('#type').val();
                if (selectedType === 'none') {
                  $('#link').prop('disabled', true).val('');
                } else {
                  $('#link').prop('disabled', false);
                }
              }

              // Initial check
              toggleLinkField();

              // On change
              $('#type').on('change', toggleLinkField);

              $(function () {
                const quill = new Quill('#full-editor', {
                  theme: 'snow',
                  modules: {
                    formula: true,
                    toolbar: [
                      [{ font: [] }, { size: [] }],
                      ['bold', 'italic', 'underline', 'strike'],
                      [{ color: [] }, { background: [] }],
                      [{ script: 'super' }, { script: 'sub' }],
                      [{ header: [1, 2, false] }, 'blockquote', 'code-block'],
                      [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
                      [{ direction: 'rtl' }],
                      ['link', 'image', 'video', 'formula'],
                      ['clean']
                    ]
                  }
                });

                // Sync to hidden input
                const $desc = $('#description');
                $desc.val(quill.root.innerHTML);
                quill.on('text-change', () => $desc.val(quill.root.innerHTML));
                $('form').on('submit', () => $desc.val(quill.root.innerHTML));
              });
            });
          </script>
    @endsection

