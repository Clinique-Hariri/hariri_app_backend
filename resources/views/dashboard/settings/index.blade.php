@extends('layouts/contentNavbarLayout')

@section('title', __('Settings'))

@section('content')
    <form class="form-horizontal" action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h4 class="fw-bold py-3 mb-3">
            <span class="text-muted fw-light"></span> {{ __('Version') }}
        </h4>

        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Android') }}</h5>
                        <small class="text-muted float-end">{{ __('Android version') }}</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="android_version_number">{{ __('Version number') }}</label>
                            <input type="text" class="form-control" id="android_version_number"
                                name="android_version_number" value="{{ $settings['android_version_number'] ?? '' }}" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="android_build_number">{{ __('Build number') }}</label>
                            <input type="text" class="form-control" id="android_build_number" name="android_build_number"
                                value="{{ $settings['android_build_number'] ?? '' }}" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="android_priority">{{ __('Priority') }}</label>
                            <select class="form-select" id="android_priority" name="android_priority">
                                <option value="0">{{ __('Optional') }}</option>
                                <option value="1" @if ($settings['android_priority'] ?? '') selected @endif>
                                    {{ __('Required') }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="android_link">{{ __('Link') }}</label>
                            <textarea class="form-control" id="android_link" name="android_link">{{ $settings['android_link'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('iOS') }}</h5>
                        <small class="text-muted float-end">{{ __('iOS version') }}</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="ios_version_number">{{ __('Version number') }}</label>
                            <input type="text" class="form-control" id="ios_version_number" name="ios_version_number"
                                value="{{ $settings['ios_version_number'] ?? '' }}" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ios_build_number">{{ __('Build number') }}</label>
                            <input type="text" class="form-control" id="ios_build_number" name="ios_build_number"
                                value="{{ $settings['ios_build_number'] ?? '' }}" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ios_priority">{{ __('Priority') }}</label>
                            <select class="form-select" id="ios_priority" name="ios_priority">
                                <option value="0">{{ __('Optional') }}</option>
                                <option value="1" @if ($settings['ios_priority'] ?? '') selected @endif>
                                    {{ __('Required') }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ios_link">{{ __('Link') }}</label>
                            <textarea class="form-control" id="ios_link" name="ios_link">{{ $settings['android_link'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <h4 class="fw-bold py-3 mb-3">
            <span class="text-muted fw-light"></span> {{ __('Contact Information') }}
        </h4>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Clinic') }}</h5>
                        <small class="text-muted float-end">{{ __('Clinic contact information') }}</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="clinic_whatsapp">{{ __('Phone Number') }}</label>
                            <input type="tel" class="form-control" id="clinic_whatsapp" name="clinic_whatsapp"
                                value="{{ $settings['clinic_whatsapp'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="clinic_email">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="clinic_email" name="clinic_email"
                                value="{{ $settings['clinic_email'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="clinic_facebook">{{ __('Facebook') }}</label>
                            <input type="url" class="form-control" id="clinic_facebook" name="clinic_facebook"
                                value="{{ $settings['clinic_facebook'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="clinic_instagram">{{ __('Instagram') }}</label>
                            <input type="url" class="form-control" id="clinic_instagram" name="clinic_instagram"
                                value="{{ $settings['clinic_instagram'] ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Pharmacy') }}</h5>
                        <small class="text-muted float-end">{{ __('Pharmacy contact information') }}</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="pharmacy_whatsapp">{{ __('Phone Number') }}</label>
                            <input type="tel" class="form-control" id="pharmacy_whatsapp" name="pharmacy_whatsapp"
                                value="{{ $settings['pharmacy_whatsapp'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="pharmacy_email">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="pharmacy_email" name="pharmacy_email"
                                value="{{ $settings['pharmacy_email'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="pharmacy_facebook">{{ __('Facebook') }}</label>
                            <input type="url" class="form-control" id="pharmacy_facebook" name="pharmacy_facebook"
                                value="{{ $settings['pharmacy_facebook'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="pharmacy_instagram">{{ __('Instagram') }}</label>
                            <input type="url" class="form-control" id="pharmacy_instagram" name="pharmacy_instagram"
                                value="{{ $settings['pharmacy_instagram'] ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Information -->
        <h4 class="fw-bold py-3 mb-3">
            <span class="text-muted fw-light"></span> {{ __('Financial Information') }}
        </h4>
        <div class="row">
            <div class="col-md-9">
                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Left side content remains the same -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="ccp">{{ __('CCP Account') }}</label>
                                <input type="text" class="form-control" id="ccp" name="ccp"
                                    value="{{ $settings['ccp'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="baridi">{{ __('Baridi Mob') }}</label>
                                <input type="text" class="form-control" id="baridi" name="baridi"
                                    value="{{ $settings['baridi'] ?? '' }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label class="form-label" for="chargily_pk">{{ __('Chargily Public Key') }}</label>
                                <input type="text" class="form-control" id="chargily_pk" name="chargily_pk"
                                    value="{{ $settings['chargily_pk'] ?? '' }}">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label" for="chargily_sk">{{ __('Chargily Secret Key') }}</label>
                                <input type="password" class="form-control" id="chargily_sk" name="chargily_sk"
                                    value="{{ $settings['chargily_sk'] ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="chargily_mode">{{ __('Chargily Mode') }}</label>
                                <select class="form-select" id="chargily_mode" name="chargily_mode">
                                    <option value="test" @if (($settings['chargily_mode'] ?? '') === 'test') selected @endif>
                                        {{ __('Test') }}</option>
                                    <option value="live" @if (($settings['chargily_mode'] ?? '') === 'live') selected @endif>
                                        {{ __('Live') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            {{-- <label class="form-label">{{ __('Payment Methods') }}</label> --}}

                            <!-- Cash Payment -->
                            <input type="hidden" name="cash_enabled" value="0">
                            <div class="form-check form-switch my-3">
                                <input class="form-check-input" type="checkbox" id="cash_enabled" name="cash_enabled"
                                    value="1" @if ($settings['cash_enabled'] ?? false) checked @endif>
                                <label class="form-check-label"
                                    for="cash_enabled">{{ __('Enable Cash Payment') }}</label>
                            </div>

                            <!-- Baridi Mob -->
                            <input type="hidden" name="baridi_enabled" value="0">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="baridi_enabled"
                                    name="baridi_enabled" value="1" @if ($settings['baridi_enabled'] ?? false) checked @endif>
                                <label class="form-check-label"
                                    for="baridi_enabled">{{ __('Enable Baridi Mob') }}</label>
                            </div>

                            <!-- CCP -->
                            <input type="hidden" name="ccp_enabled" value="0">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="ccp_enabled" name="ccp_enabled"
                                    value="1" @if ($settings['ccp_enabled'] ?? false) checked @endif>
                                <label class="form-check-label" for="ccp_enabled">{{ __('Enable CCP') }}</label>
                            </div>

                            <!-- Chargily -->
                            <input type="hidden" name="chargily_enabled" value="0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="chargily_enabled"
                                    name="chargily_enabled" value="1"
                                    @if ($settings['chargily_enabled'] ?? false) checked @endif>
                                <label class="form-check-label"
                                    for="chargily_enabled">{{ __('Enable Chargily') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="fw-bold py-3 mb-3">
            <span class="text-muted fw-light"></span> {{ __('Alerts Settings') }}
        </h4>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="pusher_instance_id">{{ __('Pusher Instance ID') }}</label>
                        <input type="text" class="form-control" id="pusher_instance_id" name="pusher_instance_id"
                            value="{{ $settings['pusher_instance_id'] ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="pusher_primary_key">{{ __('Pusher Primary Key') }}</label>
                        <input type="password" class="form-control" id="pusher_primary_key" name="pusher_primary_key"
                            value="{{ $settings['pusher_primary_key'] ?? '' }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery Settings -->
        <h4 class="fw-bold py-3 mb-3">
            <span class="text-muted fw-light"></span> {{ __('Delivery Settings') }}
        </h4>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="price_per_km">{{ __('Price Per Kilometer') }}</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="price_per_km" name="price_per_km"
                                value="{{ $settings['price_per_km'] ?? '' }}">
                            <span class="input-group-text">{{ __('DA') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="free_threshold">{{ __('Free Delivery Threshold') }}</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="free_threshold" name="free_threshold"
                                value="{{ $settings['free_threshold'] ?? '' }}">
                            <span class="input-group-text">{{ __('DA') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="min_price">{{ __('Minimum Delivery Price') }}</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="min_price" name="min_price"
                                value="{{ $settings['min_price'] ?? '' }}">
                            <span class="input-group-text">{{ __('DA') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="max_price">{{ __('Maximum Delivery Price') }}</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="max_price" name="max_price"
                                value="{{ $settings['max_price'] ?? '' }}">
                            <span class="input-group-text">{{ __('DA') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Module Settings -->
        <h4 class="fw-bold py-3 mb-3">
            <span class="text-muted fw-light"></span> {{ __('Module Settings') }}
        </h4>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Clinic -->
                        <input type="hidden" name="clinic_enabled" value="0">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="clinic_enabled" name="clinic_enabled"
                                value="1" @if ($settings['clinic_enabled'] ?? false) checked @endif>
                            <label class="form-check-label" for="clinic_enabled">{{ __('Enable Clinic Module') }}</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Pharmacy -->
                        <input type="hidden" name="pharmacy_enabled" value="0">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="pharmacy_enabled" name="pharmacy_enabled"
                                value="1" @if ($settings['pharmacy_enabled'] ?? false) checked @endif>
                            <label class="form-check-label" for="pharmacy_enabled">{{ __('Enable Pharmacy Module') }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3" style="text-align: center">
            <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
        </div>
    </form>

@endsection
