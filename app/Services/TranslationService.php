<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class TranslationService
{
    // Get translation rows with optional filtering by status (missing, identical, translated)
    public function rows(string $source, string $target, string $status = 'all'): array
    {
        $sourceItems = $this->read($source);
        $targetItems = $this->read($target);

        $keys = collect(array_unique([
            ...array_keys($sourceItems),
            ...array_keys($targetItems),
        ]))->sort()->values();

        return $keys->map(function (string $key) use ($sourceItems, $targetItems) {
            $sourceText = $sourceItems[$key] ?? '';
            $targetText = $targetItems[$key] ?? '';

            $rowStatus = blank($targetText)
                ? 'missing'
                : ($targetText === $sourceText ? 'identical' : 'translated');

            return compact('key', 'sourceText', 'targetText', 'rowStatus');
        })
        ->when($status !== 'all', fn ($c) => $c->where('rowStatus', $status))
        ->values()
        ->all();
    }

    // Helper method to save a single translation key-value pair to a locale file
    public function save(string $locale, string $key, string $value): void
    {
        $items = $this->read($locale);
        $items[$key] = $value;
        ksort($items);

        $this->write($locale, $items);
    }

    // Helper method to merge an array of translations into a locale file
    public function merge(string $locale, array $translated): void
    {
        $items = array_merge($this->read($locale), $translated);
        ksort($items);

        $this->write($locale, $items);
    }

    // Helper method to read translations from a locale file
    public function read(string $locale): array
    {
        $path = lang_path("{$locale}.json");

        return File::exists($path)
            ? (json_decode(File::get($path), true) ?: [])
            : [];
    }

    // Get translation statistics for a given source and target locale
    public function stats(string $source, string $target): array
    {
        $rows       = collect($this->rows($source, $target));
        $total      = $rows->count();
        $translated = $rows->where('rowStatus', 'translated')->count();
        $missing    = $rows->where('rowStatus', 'missing')->count();
        $identical  = $rows->where('rowStatus', 'identical')->count();
        $percent    = $total > 0 ? round(($translated / $total) * 100) : 0;

        return compact('total', 'translated', 'missing', 'identical', 'percent');
    }

    // Helper method to sort translation keys in a locale file alphabetically
    public function sort(string $locale): void
    {
        $items = $this->read($locale);
        ksort($items);

        $this->write($locale, $items);
    }

    // Get a list of available locales based on the JSON files in the lang directory
    public function locales(): array
    {
        return collect(File::files(lang_path()))
            ->filter(fn ($f) => $f->getExtension() === 'json')
            ->map(fn ($f) => $f->getFilenameWithoutExtension())
            ->values()
            ->all();
    }

    // Helper method to write translation items to a locale file
    public function write(string $locale, array $items): void
    {
        File::ensureDirectoryExists(lang_path());
        File::put(
            lang_path("{$locale}.json"),
            json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }
}
