<p class="text-muted mb-2">
    {{ __('Configure your site\'s API settings, including API keys and endpoints.') }}
</p>

<div class="row g-3">
    <div class="col-md-12">
        <x-input name="gemini_api_key" :label="__('Gemini API Key')" :value="$settings->gemini_api_key" type="password" />
    </div>
</div>
