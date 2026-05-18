<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('api', function ($blueprint) {
            $blueprint->add('gemini_api_key', null);
        });
    }
};
