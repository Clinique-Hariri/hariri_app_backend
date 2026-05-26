@extends('layouts.contentNavbarLayout')

@section('title', $edit ? __('blog::app.edit') . ' ' . __('blog::app.author') : __('blog::app.create') . ' ' . __('blog::app.author'))

@section('content')
    <!-- Header Actions -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1 mt-3">{{ __($edit ? 'app.edit' : 'app.add') }} {{ __('blog::app.author') }}</h4>
            <p class="text-muted">{{ __('blog::app.author_info') }}</p>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-3">
            @permission(\App\Support\Enum\PermissionNames::MANAGE_BLOG_AUTHORS)
                <a href="{{ route('blog.authors.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
                </a>
            @endpermission
            <button type="submit" form="authorForm" class="btn btn-primary">
                <i class="bx bx-save me-1"></i>{{ __('app.send') }}
            </button>
        </div>
    </div>

    @if ($edit)
        <form id="authorForm" action="{{ route('blog.authors.update', $model->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PATCH')
        @else
            <form id="authorForm" action="{{ route('blog.authors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
    @endif

    <!-- First Row: Author Information and Image -->
    <div class="row mb-4">
        <!-- Author Information -->
        <div class="col-12 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('blog::app.basic_details') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Author Name -->
                    <div class="mb-3">
                        <label class="form-label">{{ __('blog::app.fullname') }} <span class="text-danger">*</span></label>
                        <input type="text" name="fullname" class="form-control @error('fullname') is-invalid @enderror"
                            placeholder="{{ __('blog::app.fullname') }}"
                            value="{{ old('fullname', $edit ? $model->fullname : '') }}" required>
                    </div>

                    <!-- Bio -->
                    <div class="mb-3">
                        <label class="form-label">{{ __('blog::app.bio') }}</label>
                        <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="4"
                            placeholder="{{ __('blog::app.bio') }}">{{ old('bio', $edit ? $model->bio : '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Media -->
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 card-title">{{ __('blog::app.avatar') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center gap-4 mb-4">
                        @if ($edit && $model->getFirstMediaUrl('image'))
                            <img src="{{ $model->getFirstMediaUrl('image') }}" alt="Author Image" class="d-block rounded"
                                height="200" width="200" id="uploadedAvatar">
                        @else
                            <img src="{{ asset('assets/img/avatars/9.png') }}" alt="Placeholder" class="d-block rounded"
                                height="200" width="200" id="uploadedAvatar">
                        @endif
                        <div class="button-wrapper text-center">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">{{ __('app.new_image') }}</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" name="image" class="account-file-input" hidden
                                    accept="image/png, image/jpeg, image/gif" />
                            </label>
                            <button type="button" class="btn btn-label-secondary account-image-reset mb-4">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">{{ __('app.reset') }}</span>
                            </button>
                            <p class="text-muted mb-0">{{ __('app.allowed_formats') }}: JPG, PNG, GIF.
                                {{ __('app.max_size') }}: 2MB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </form>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            // Update/reset author image
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
