<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public bool    $app_debug                       = true;
    public string  $site_name                       = 'Zonix';
    public ?string $site_description                = 'Simple and powerful admin dashboard template built with Laravel and Bootstrap.';
    public ?string $site_footer                     = 'Designed and Developed by <a href="https://masrizky.com" target="_blank">Rizky Hidayatullah</a>';
    public ?string $site_copyright                  = '2026 © Zonix. All rights reserved.';
    public ?string $site_logo                       = null;
    public ?string $site_favicon                    = null;
    public string  $site_email                      = 'mail@masrizky.com';
    public bool    $maintenance_mode                = false;
    public string  $timezone                        = 'Asia/Jakarta';
    public string  $locale                          = 'en';
    public string  $date_format                     = 'Y-m-d';
    public string  $time_format                     = 'H:i:s';
    public bool    $enable_registration             = true;
    public bool    $enable_email_verification       = true;
    public bool    $enable_recaptcha                = false;
    public ?string $recaptcha_site_key              = null;
    public ?string $recaptcha_secret_key            = null;
    public bool    $enable_google_analytics         = false;
    public ?string $google_analytics_tracking_id    = null;

    // Meta
    public ?string $meta_author                     = 'Rizky Hidayatullah';
    public ?string $meta_description                = 'Zonix is a powerful and flexible admin dashboard template built with Laravel and Bootstrap. It offers a wide range of features and components to help you create stunning and functional admin interfaces with ease.';
    public ?string $meta_keywords                   = 'admin dashboard, laravel, bootstrap, template, ui kit, responsive, customizable';

    public static function group(): string
    {
        return 'general';
    }

    public static function encrypted(): array
    {
        return [
            'recaptcha_site_key',
            'recaptcha_secret_key',
        ];
    }
}
