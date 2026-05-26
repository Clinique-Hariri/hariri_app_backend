@extends('layouts/contentNavbarLayout')

@section('title', __('app.user'))

@section('content')
  <h4 class="fw-bold py-3 mb-3 row justify-content-between">
    <div class="col-md-auto">
      <span class="text-muted fw-light">{{ __($edit ? 'app.edit' : 'app.add') }}/</span> @lang('app.user')
    </div>
    <div class="col-md-auto">
      @permission(\App\Support\Enum\PermissionNames::MANAGE_USERS)
      <a href="{{ route('users.index') }}" class="text-white text-decoration-none">
        <button type="button" class="btn btn-primary" style="float: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
          <span class="tf-icons bx bx-arrow-back"></span> @lang('app.back')
        </button>
      </a>
      @endpermission
    </div>
  </h4>

  @if ($edit)
    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PATCH')
      @else
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @endif

          <div class="row">
            <div class="col-xl">
              <div class="card mb-4">
                <div class="row card-body">
                  <div class="mb-3 col-md-6">
                    <label for="firstname" class="form-label">{{ __('user.firstname') }}</label>
                    <input type="text" name="firstname" class="form-control" id="firstname"
                           placeholder="{{ __('user.firstname') }}"
                           value="{{ old('firstname', $user->firstname ?? '') }}" required>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label for="lastname" class="form-label">{{ __('user.lastname') }}</label>
                    <input type="text" name="lastname" class="form-control" id="lastname"
                           placeholder="{{ __('user.lastname') }}" value="{{ old('lastname', $user->lastname ?? '') }}"
                           required>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label for="email" class="form-label">{{ __('user.email') }}</label>
                    <input type="text" name="email" class="form-control" id="email" placeholder="{{ __('user.email') }}"
                           value="{{ old('email', $user->email ?? '') }}" required>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label for="phone" class="form-label">{{ __('user.phone') }}</label>
                    <input type="text" name="phone" class="form-control phone-mask" id="phone"
                           placeholder="{{ __('user.phone') }}" value="{{ old('phone', $user->phone ?? '') }}" required>
                  </div>

                  @if(!$edit)
                    <div class="mb-3">
                      <div class="form-password-toggle">
                        <label for="password" class="form-label">{{ __('user.password') }}</label>
                        <div class="input-group input-group-merge">
                          <input type="text" name="password" class="form-control" id="password"
                                 placeholder="············" value="{{ old('password') }}" required>
                          <span class="input-group-text cursor-pointer" id="basic-default-password"><i
                              class="bx bx-show"></i></span>
                        </div>
                      </div>
                    </div>
                  @endif

                  <div class="mb-3 col-md-6">
                    <label for="gender" class="form-label">{{ __('user.gender') }}</label>
                    <select name="gender" class="form-select" id="gender" required>
                      <option value="">{{ __('app.select_option') }}</option>
                      @foreach(\App\Constants\Gender::all(true) as $key => $value)
                        <option
                          value="{{ $key }}" {{ old('gender', $user->gender ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="type" class="form-label">{{ __('user.type') }}</label>
                    <select name="type" class="form-select" id="type" required>
                      <option value="">{{ __('app.select_option') }}</option>
                      @foreach(\App\Support\Enum\UserTypes::all(true) as $key => $value)
                        <option
                          value="{{ $key }}" {{ old('type', $user->type ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="role" class="form-label">{{ __('user.role') }}</label>
                    <select name="role" class="form-select" id="role" required disabled>
                      <option value="">{{ __('app.select_option') }}</option>
                    </select>
                  </div>

                  <div class="mb-3 col-md-6">
                    <label for="avatar" class="form-label">{{ __('user.avatar') }}</label>
                    <div class="input-group">
                      <input type="file" name="avatar" class="form-control" id="avatar"
                             placeholder="{{ __('user.avatar') }}">
                    </div>
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
            @php
              $adminOptions = array_merge(
                  ['' => __('app.select_option')],
                  \App\Support\Enum\UserRoles::all()
              );
            @endphp

            const secondSelectOptions = {
              'admin': @json($adminOptions),
            };

            const typeSelect = document.getElementById('type');
            const roleSelect = document.getElementById('role');

            function populateRoleOptions(selectedValue) {
              roleSelect.innerHTML = '';

              if (selectedValue && secondSelectOptions[selectedValue]) {
                roleSelect.removeAttribute('disabled');
                for (let key in secondSelectOptions[selectedValue]) {
                  let option = document.createElement('option');
                  option.value = key;
                  option.textContent = secondSelectOptions[selectedValue][key];
                  roleSelect.appendChild(option);
                }
              } else {
                roleSelect.setAttribute('disabled', 'disabled');
                let defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '{{ __('app.select_option') }}';
                roleSelect.appendChild(defaultOption);
              }
            }

            typeSelect.addEventListener('change', function() {
              populateRoleOptions(this.value);
            });

            document.addEventListener('DOMContentLoaded', function() {
              @if ($edit)
              const initialType = typeSelect.value;
              const initialRole = '{{ old('role', $user->getRoleNames()->first() ?? '') }}';
              populateRoleOptions(initialType);
              if (initialRole) {
                roleSelect.value = initialRole;
              }
              @endif
            });
          </script>
    @endsection
