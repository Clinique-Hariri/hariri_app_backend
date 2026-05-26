@extends('layouts/contentNavbarLayout')

@section('title', __('blog::app.blog'))

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('content')
    <!-- Header Actions -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1 mt-3">{{ __($edit ? 'app.edit' : 'app.add') }} {{ __('blog::app.blog') }}</h4>
            <p class="text-muted">{{ __('blog::app.blog_info') }}</p>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-3">
            @permission(\App\Support\Enum\PermissionNames::MANAGE_BLOGS)
                <a href="{{ route('blogs.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
                </a>
            @endpermission
            <button type="submit" form="blogForm" class="btn btn-primary">
                <i class="bx bx-save me-1"></i>{{ __('app.send') }}
            </button>
        </div>
    </div>

    @if ($edit)
        <form id="blogForm" action="{{ route('blogs.update', $model->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
        @else
            <form id="blogForm" action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
    @endif

    @php
        // Determine current author selection
        $currentAuthorValue = '';
        if (old('author')) {
            $currentAuthorValue = old('author');
        } elseif ($edit && $model->authorable_id) {
            $currentAuthorValue = $model->isAuthorDoctor()
                ? 'doctor_' . $model->authorable_id
                : 'author_' . $model->authorable_id;
        }

        // Determine current category selection
        $currentCategoryValue = '';
        if (old('category')) {
            $currentCategoryValue = old('category');
        } elseif ($edit && $model->categoriable_id) {
            $currentCategoryValue = $model->isCategorySpeciality()
                ? 'speciality_' . $model->categoriable_id
                : 'category_' . $model->categoriable_id;
        }
    @endphp

    <!-- Main Content Row -->
    <div class="row mb-4">
        <!-- Left Column: Blog Information -->
        <div class="col-12 col-lg-8">
            <!-- Basic Details Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('blog::app.basic_details') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Title -->
                    <div class="mb-3">
                        <label class="form-label" for="title">
                            {{ __('blog::app.title') }}
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" id="title"
                            class="form-control @error('title') is-invalid @enderror"
                            placeholder="{{ __('blog::app.title') }}"
                            value="{{ old('title', $edit ? $model->title : '') }}" required>
                    </div>

                    <!-- Content -->
                    <div class="mb-3">
                        <label class="form-label" for="content">
                            {{ __('blog::app.content') }}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="form-control @error('content') is-invalid @enderror p-0">
                            <div class="comment-editor border-0 pb-4" id="content-editor">
                                {!! old('content', $edit ? $model->content : '') !!}
                            </div>
                            <input type="hidden" name="content" id="content">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Background Image Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('blog::app.background_image') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-4">
                        @if ($edit && $model->background_image)
                            <img src="{{ $model->background_image_url }}" alt="Background Image" class="d-block rounded"
                                height="150" width="250" id="uploadedAvatar" style="object-fit: cover;">
                        @else
                            <img src="{{ asset('assets/img/backgrounds/1.jpg') }}" alt="Placeholder"
                                class="d-block rounded" height="150" width="400" id="uploadedAvatar"
                                style="object-fit: cover;">
                        @endif
                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-2" tabindex="0">
                                <span class="d-none d-sm-block">{{ __('app.new_image') }}</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" name="background_image" class="account-file-input"
                                    hidden accept="image/png, image/jpeg, image/gif" />
                            </label>
                            <button type="button" class="btn btn-label-secondary account-image-reset mb-2">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">{{ __('app.reset') }}</span>
                            </button>
                            <p class="text-muted mb-0 small">{{ __('app.allowed_formats') }}: JPG, PNG, GIF.
                                {{ __('app.max_size') }}: 2MB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Publishing & Author/Category -->
        <div class="col-12 col-lg-4">
            <!-- Author & Category Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('blog::app.author_and_category') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Author Selection -->
                    <div class="mb-3">
                        <label class="form-label" for="author">
                            {{ __('blog::app.author') }}
                            <span class="text-danger">*</span>
                        </label>
                        <select name="author" class="form-select select2 @error('author') is-invalid @enderror"
                            id="author" data-placeholder="{{ __('app.select_option') }}" required>
                            <option value="">{{ __('app.select_option') }}</option>
                            @if ($authors->count() > 0)
                                <optgroup label="{{ __('blog::app.blog_authors') }}">
                                    @foreach ($authors as $author)
                                        <option value="author_{{ $author->id }}"
                                            {{ $currentAuthorValue === 'author_' . $author->id ? 'selected' : '' }}>
                                            {{ $author->fullname }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if ($doctors->count() > 0)
                                <optgroup label="{{ __('blog::app.doctors') }}">
                                    @foreach ($doctors as $doctor)
                                        <option value="doctor_{{ $doctor->id }}"
                                            {{ $currentAuthorValue === 'doctor_' . $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->fullname }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div>

                    <!-- Category Selection -->
                    <div class="mb-3">
                        <label class="form-label" for="category">
                            {{ __('blog::app.category') }}
                            <span class="text-danger">*</span>
                        </label>
                        <select name="category" class="form-select select2 @error('category') is-invalid @enderror"
                            id="category" data-placeholder="{{ __('app.select_option') }}" required>
                            <option value="">{{ __('app.select_option') }}</option>
                            @if ($categories->count() > 0)
                                <optgroup label="{{ __('blog::app.blog_categories') }}">
                                    @foreach ($categories as $category)
                                        <option value="category_{{ $category->id }}"
                                            {{ $currentCategoryValue === 'category_' . $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if ($specialities->count() > 0)
                                <optgroup label="{{ __('blog::app.specialities') }}">
                                    @foreach ($specialities as $speciality)
                                        <option value="speciality_{{ $speciality->id }}"
                                            {{ $currentCategoryValue === 'speciality_' . $speciality->id ? 'selected' : '' }}>
                                            {{ $speciality->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <!-- Publishing Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('blog::app.publishing') }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="published_at">
                            {{ __('blog::app.published_at') }}
                        </label>
                        <input type="text" name="published_at" id="published_at"
                            class="form-control flatpickr-datetime @error('published_at') is-invalid @enderror"
                            placeholder="{{ __('blog::app.published_at') }}">
                        <small class="text-muted">{{ __('blog::app.leave_empty_for_draft') }}</small>
                    </div>

                    @if ($edit)
                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="mb-0 h6">{{ __('blog::app.clicks_count') }}</span>
                            <span class="badge bg-label-info">{{ $model->clicks_count }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    </form>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            // Initialize Flatpickr for datetime
            $('.flatpickr-datetime').flatpickr({
                enableTime: true,
                dateFormat: 'Y-m-d H:i',
                time_24hr: true,
                defaultDate: "{{ old('published_at', $edit && $model->published_at ? $model->published_at->format('Y-m-d H:i') : '') }}"
            });

            // Initialize Quill Editor for content
            createBasicTextEditor('#content-editor');
            const $content = $('#content');
            $content.val(quill.root.innerHTML);
            quill.on('text-change', () => $content.val(quill.root.innerHTML));
            $('#blogForm').on('submit', () => $content.val(quill.root.innerHTML));

            // Update/reset background image
            let accountUserImage = document.getElementById('uploadedAvatar');
            const fileInput = document.querySelector('.account-file-input'),
                resetFileInput = document.querySelector('.account-image-reset');

            if (accountUserImage) {
                const resetImage = accountUserImage.src;
                fileInput.onchange = () => {
                    if (fileInput.files[0]) {
                        accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
                    }
                };
                resetFileInput.onclick = () => {
                    fileInput.value = '';
                    accountUserImage.src = resetImage;
                };
            }
        });
    </script>
@endsection
