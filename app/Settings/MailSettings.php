<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MailSettings extends Settings
{
    public string  $driver          = 'smtp';
    public string  $host            = 'smtp.mailtrap.io';
    public int     $port            = 587;
    public ?string $username        = null;
    public ?string $password        = null;
    public ?string $encryption      = null;
    public ?string $from_address    = null;
    public ?string $from_name       = null;

    public static function group(): string
    {
        return 'mail';
    }

    public static function encrypted(): array
    {
        return [
            'username',
            'password',
            'from_address',
        ];
    }
}
