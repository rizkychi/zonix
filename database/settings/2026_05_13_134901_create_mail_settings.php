<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('mail', function ($blueprint) {
            $blueprint->add('driver', 'smtp');
            $blueprint->add('host', 'smtp.mailtrap.io');
            $blueprint->add('port', 587);
            $blueprint->add('username', null);
            $blueprint->add('password', null);
            $blueprint->add('encryption', null);
            $blueprint->add('from_address', null);
            $blueprint->add('from_name', null);
        });
    }
};
