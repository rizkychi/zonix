<h5>{{ __('Site Information') }}</h5>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <x-input name="site_name" :label="__('Site Name')" :value="$settings->site_name" required />
    </div>
    <div class="col-md-6">
        <x-input name="site_email" :label="__('Site Email')" :value="$settings->site_email" required type="email" />
    </div>
    <div class="col-md-12">
        <label for="site_description">{{ __('Site Description') }}</label>
        <textarea class="form-control @error('site_description') is-invalid @enderror" name="site_description" id="site_description" rows="2">{{ $settings->site_description }}</textarea>
        @error('site_description')
            <div class="invalid-feedback server-error d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-12">
        <x-input name="site_footer" :label="__('Site Footer Text')" :value="$settings->site_footer" />
    </div>
    <div class="col-md-12">
        <x-input name="site_copyright" :label="__('Site Copyright Text')" :value="$settings->site_copyright" />
    </div>
    <div class="col-md-6">
        <x-input name="site_logo" :label="__('Site Logo')" type="file" accept="image/*" />
        @if ($settings->site_logo)
            <div class="mt-2">
                <img src="{{ Storage::url($settings->site_logo) }}" alt="Site Logo" height="50">
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <x-input name="site_favicon" :label="__('Site Favicon')" type="file" accept="image/*" />
        @if ($settings->site_favicon)
            <div class="mt-2">
                <img src="{{ Storage::url($settings->site_favicon) }}" alt="Site Favicon" height="50">
            </div>
        @endif
    </div>
</div>

<h5>{{ __('Localization & Features') }}</h5>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="locale">{{ __('Locale') }} <span class="text-danger">*</span></label>
        <select class="form-select select2 @error('locale') is-invalid @enderror" name="locale" id="locale" data-placeholder="{{ __('Select Locale') }}" required>
            <option value=""></option>
            @foreach (app(App\Services\TranslationService::class)->locales() as $locale)
                <option value="{{ $locale }}" {{ $settings->locale === $locale ? 'selected' : '' }}>
                    {{ strtoupper($locale) }}
                </option>
            @endforeach
        </select>
        @error('locale')
            <div class="invalid-feedback server-error d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="timezone">{{ __('Time Zone') }} <span class="text-danger">*</span></label>
        <select class="form-select select2 @error('timezone') is-invalid @enderror" name="timezone" id="timezone" data-placeholder="{{ __('Select Time Zone') }}" required>
            <option value=""></option>
            @foreach (timezone_identifiers_list() as $tz)
                <option value="{{ $tz }}" {{ $settings->timezone === $tz ? 'selected' : '' }}>
                    {{ $tz }}
                </option>
            @endforeach
        </select>
        @error('timezone')
            <div class="invalid-feedback server-error d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6">
        <x-input name="date_format" :label="__('Date Format')" :value="$settings->date_format" required />
    </div>
    <div class="col-md-6">
        <x-input name="time_format" :label="__('Time Format')" :value="$settings->time_format" required />
    </div>
    <div class="col-md-12">
        <div class="form-check form-switch d-flex justify-content-between ps-0" style="max-width: 250px">
            <label class="form-check-label" for="maintenance_mode">{{ __('Maintenance Mode') }}</label>
            <input class="form-check-input" type="checkbox" value="1" role="switch" id="maintenance_mode" name="maintenance_mode" {{ $settings->maintenance_mode ? 'checked' : '' }}>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-check form-switch d-flex justify-content-between ps-0" style="max-width: 250px">
            <label class="form-check-label" for="enable_registration">{{ __('User Registration') }}</label>
            <input class="form-check-input" type="checkbox" value="1" role="switch" id="enable_registration" name="enable_registration" {{ $settings->enable_registration ? 'checked' : '' }}>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-check form-switch d-flex justify-content-between ps-0" style="max-width: 250px">
            <label class="form-check-label" for="enable_email_verification">{{ __('Email Verification') }}</label>
            <input class="form-check-input" type="checkbox" value="1" role="switch" id="enable_email_verification" name="enable_email_verification" {{ $settings->enable_email_verification ? 'checked' : '' }}>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-check form-switch d-flex justify-content-between ps-0" style="max-width: 250px">
            <label class="form-check-label" for="enable_recaptcha">{{ __('reCAPTCHA') }}</label>
            <input class="form-check-input" type="checkbox" value="1" role="switch" id="enable_recaptcha" name="enable_recaptcha" {{ $settings->enable_recaptcha ? 'checked' : '' }}>
        </div>
    </div>
    <div class="col-md-6 {{ !$settings->enable_recaptcha ? 'collapse' : '' }}">
        <x-input name="recaptcha_site_key" :label="__('reCAPTCHA Site Key')" :value="$settings->recaptcha_site_key" type="password" />
    </div>
    <div class="col-md-6 {{ !$settings->enable_recaptcha ? 'collapse' : '' }}">
        <x-input name="recaptcha_secret_key" :label="__('reCAPTCHA Secret Key')" :value="$settings->recaptcha_secret_key" type="password" />
    </div>
    <div class="col-md-12">
        <div class="form-check form-switch d-flex justify-content-between ps-0" style="max-width: 250px">
            <label class="form-check-label" for="enable_google_analytics">{{ __('Google Analytics') }}</label>
            <input class="form-check-input" type="checkbox" value="1" role="switch" id="enable_google_analytics" name="enable_google_analytics" {{ $settings->enable_google_analytics ? 'checked' : '' }}>
        </div>
    </div>
    <div class="col-md-12 {{ !$settings->enable_google_analytics ? 'collapse' : '' }}">
        <x-input name="google_analytics_tracking_id" :label="__('Google Analytics Tracking ID')" :value="$settings->google_analytics_tracking_id" />
    </div>
</div>

<h4>{{ __('Metadata') }}</h4>
<div class="row g-3">
    <div class="col-md-12">
        <x-input name="meta_author" :label="__('Meta Author')" :value="$settings->meta_author" />
    </div>
    <div class="col-md-12">
        <label for="meta_description">{{ __('Meta Description') }}</label>
        <textarea class="form-control @error('meta_description') is-invalid @enderror" name="meta_description" id="meta_description" rows="2">{{ $settings->meta_description }}</textarea>
        @error('meta_description')
            <div class="invalid-feedback server-error d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-12">
        <x-input name="meta_keywords" :label="__('Meta Keywords (comma separated)')" :value="$settings->meta_keywords" />
    </div>
</div>