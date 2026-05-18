<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            $this->applyGeneralSettings();
            $this->applyMailSettings();
            $this->applyApiSettings();
        } catch (\Exception $e) {
            // Silent fail - fallback to env settings if database is not available
        }
    }

    private function applyGeneralSettings(): void
    {
        $settings = app(GeneralSettings::class);

        config([
            'app.name' => $settings->site_name,
            'app.timezone' => $settings->timezone,
            'app.locale' => $settings->locale,
            'app.debug' => $settings->app_debug,
        ]);
    }

    private function applyMailSettings(): void
    {
        $settings = app(MailSettings::class);

        config([
            'mail.default' => $settings->driver,
            'mail.mailers.smtp.host' => $settings->host,
            'mail.mailers.smtp.port' => $settings->port,
            'mail.mailers.smtp.username' => $settings->username,
            'mail.mailers.smtp.password' => $settings->password,
            'mail.mailers.smtp.encryption' => $settings->encryption,
            'mail.from.address' => $settings->from_address,
            'mail.from.name' => $settings->from_name,
        ]);
    }

    private function applyApiSettings(): void
    {
        $settings = app(ApiSettings::class);

        // Apply API settings to config or services as needed
        // For example, if you have a Gemini API client, you could set the API key here
        // config(['ai.providers.gemini.api_key' => $settings->gemini_api_key]);

        config([
            'ai.providers.gemini.key' => $settings->gemini_api_key, 
        ]);
    }
}
