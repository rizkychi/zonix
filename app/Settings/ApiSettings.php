<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ApiSettings extends Settings
{
    public ?string $gemini_api_key      = null;

    public static function group(): string
    {
        return 'api';
    }

    public static function encrypted(): array
    {
        return [
            'gemini_api_key',
        ];
    }
}