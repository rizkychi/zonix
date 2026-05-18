<p class="text-muted mb-2">
    {{ __('Configure your site\'s general settings, including site name, logo, email, and more.') }}
</p>

<div class="row g-3">
    <div class="col-md-6">
        <x-input name="site_name" :label="__('Site Name')" :value="$settings->site_name" required />
    </div>
    <div class="col-md-6">
        <x-input name="site_email" :label="__('Site Email')" :value="$settings->site_email" required type="email" />
    </div>
</div>