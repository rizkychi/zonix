<?php

namespace App\Console\Commands\Translation;

use App\Services\TranslationService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('translation:scan {--locale=en : Source locale to scan and update}')]
#[Description('Scan Blade and PHP files, find __() keys, sync to locale JSON')]
class ScanMissingKeys extends Command
{
    public function handle(TranslationService $service): void
    {
        $locale = $this->option('locale');
        $existing = $service->read($locale);
        $found = [];

        $paths = [app_path(), resource_path('views')];

        foreach ($paths as $path) {
            $files = File::allFiles($path);

            foreach ($files as $file) {
                if (! in_array($file->getExtension(), ['php', 'blade.php', 'twig'])) {
                    continue;
                }

                $content = File::get($file->getPathname());

                // Catch __('key'), trans('key'), @lang('key'), {{ __('key') }}
                preg_match_all(
                    '/(?:__|trans|@lang)\(\s*[\'"](.+?)[\'"]\s*[\),]/',
                    $content,
                    $matches
                );

                foreach ($matches[1] as $key) {
                    $found[$key] = $existing[$key] ?? $key;
                }
            }
        }

        ksort($found);

        $service->write($locale, $found);

        $new = count(array_diff_key($found, $existing));
        $this->info("Scan completed. Found " . count($found) . " keys, with {$new} new keys added to {$locale}.json.");
    }
}
