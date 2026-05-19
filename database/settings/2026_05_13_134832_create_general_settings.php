<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('general', function ($blueprint) {
            $blueprint->add('app_debug', true);
            $blueprint->add('site_name', 'Zonix');
            $blueprint->add('site_description', 'Simple and powerful admin dashboard template built with Laravel and Bootstrap.');
            $blueprint->add('site_footer', 'Designed and Developed by <a href="https://masrizky.com" target="_blank">Rizky Hidayatullah</a>');
            $blueprint->add('site_copyright', '2026 © Zonix. All rights reserved.');
            $blueprint->add('site_logo', null);
            $blueprint->add('site_favicon', null);
            $blueprint->add('site_email', 'mail@masrizky.com');
            $blueprint->add('maintenance_mode', false);
            $blueprint->add('timezone', 'Asia/Jakarta');
            $blueprint->add('locale', 'en');
            $blueprint->add('date_format', 'Y-m-d');
            $blueprint->add('time_format', 'H:i:s');
            $blueprint->add('enable_registration', true);
            $blueprint->add('enable_email_verification', true);
            $blueprint->add('enable_recaptcha', false);
            $blueprint->add('recaptcha_site_key', null);
            $blueprint->add('recaptcha_secret_key', null);
            $blueprint->add('enable_google_analytics', false);
            $blueprint->add('google_analytics_tracking_id', null);

            $blueprint->add('meta_author', 'Rizky Hidayatullah');
            $blueprint->add('meta_description', 'Zonix is a powerful and flexible admin dashboard template built with Laravel and Bootstrap. It offers a wide range of features and components to help you create stunning and functional admin interfaces with ease.');
            $blueprint->add('meta_keywords', 'admin dashboard, laravel, bootstrap, template, ui kit, responsive, customizable');
        });
    }
};
