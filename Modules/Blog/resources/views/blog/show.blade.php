@extends('layouts/contentNavbarLayout')

@section('title', __('blog::app.blog'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span class="text-muted fw-light">@lang('blog::app.blogs')/</span>
      {{ $model->title['en'] ?? $model->title }}
    </div>
    <div class="col-md-auto">
      @permission(\App\Support\Enum\PermissionNames::MANAGE_BLOGS)
      <a href="{{ route('blogs.index') }}" class="text-white text-decoration-none">
        <button type="button" class="btn btn-primary" style="float: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
          <span class="tf-icons bx bx-arrow-back"></span> @lang('app.back')
        </button>
      </a>
      @endpermission
    </div>
  </h4>

  <div class="row">
    <div class="col-xl-8">
      <div class="card mb-4">
        <div class="card-body">
          @if($model->background_image)
            <img src="{{ $model->background_image_url }}" alt="Background" class="img-fluid mb-4 rounded">
          @endif

          <h2>{{ $model->title }}</h2>

          <div class="d-flex align-items-center mb-4">
            @if($model->author)
              <span class="badge bg-primary me-2">{{ $model->author->display_name }}</span>
            @endif
            @if($model->category)
              <span class="badge bg-info me-2">{{ $model->category->display_name }}</span>
            @endif
            <span class="text-muted">{{ $model->created_at->format('M d, Y') }}</span>
          </div>

          <div class="content">
            {!! nl2br(e($model->content)) !!}
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">@lang('blog::app.blog') @lang('app.details')</h5>
        </div>
        <div class="card-body">
          <ul class="list-unstyled mb-0">
            <li class="mb-2">
              <strong>@lang('blog::app.status'):</strong>
              <span class="badge bg-{{ \Modules\Blog\Constants\Blog\Status::get_color($model->status) }}">
                {{ \Modules\Blog\Constants\Blog\Status::get_name($model->status) }}
              </span>
            </li>
            <li class="mb-2">
              <strong>@lang('blog::app.clicks_count'):</strong> {{ $model->clicks_count }}
            </li>
            <li class="mb-2">
              <strong>@lang('app.created_at'):</strong> {{ $model->created_at->format('Y-m-d H:i') }}
            </li>
            <li class="mb-2">
              <strong>@lang('app.updated_at'):</strong> {{ $model->updated_at->format('Y-m-d H:i') }}
            </li>
          </ul>
        </div>
      </div>

      @permission(\App\Support\Enum\PermissionNames::MANAGE_BLOGS)
      <div class="card">
        <div class="card-body">
          <a href="{{ route('blogs.edit', $model->id) }}" class="btn btn-primary w-100 mb-2">
            <i class="bx bx-edit"></i> @lang('blog::app.edit')
          </a>
        </div>
      </div>
      @endpermission
    </div>
  </div>
@endsection
