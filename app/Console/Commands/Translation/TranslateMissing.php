<?php

namespace App\Console\Commands\Translation;

use App\Ai\Agents\TranslatorAgent;
use App\Services\TranslationService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Laravel\Ai\Enums\Lab;

#[Signature('translation:translate
                            {target}
                            {--source=en}
                            {--chunk=5}
                            {--force}')]
#[Description('Translate missing keys in the target locale using AI, based on the source locale.')]
class TranslateMissing extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(TranslationService $service): void
    {
        $source = $this->option('source');
        $target = $this->argument('target');
        $chunk  = (int) $this->option('chunk');
        $force  = $this->option('force');

        $sourceItems = $service->read($source);
        $targetItems = $service->read($target);

        $missing = collect($sourceItems)->filter(
            fn ($v, $k) => $force || blank($targetItems[$k] ?? '')
        );

        if ($missing->isEmpty()) {
            $this->info('All keys are already translated. Use --force to re-translate all keys regardless of existing translations.');
            return;
        }

        $this->info("Translating {$missing->count()} keys ({$source} → {$target})...");
        $bar   = $this->output->createProgressBar($missing->count());
        $agent = new TranslatorAgent($source, $target);

        foreach ($missing->chunk($chunk) as $batch) {
            $prompt = "Translate these key-value pairs:\n\n"
                . $batch->map(fn ($v, $k) => "key: \"{$k}\"\ntext: \"{$v}\"")->join("\n\n");

            $response = $agent->prompt(
                $prompt,
                provider: Lab::Gemini,
                model: 'gemini-2.0-flash',
                timeout: 60
            );

            $translated  = collect($response['translations'])->pluck('value', 'key')->all();
            $service->merge($target, $translated);
            $bar->advance($batch->count());

            if (! $missing->chunk($chunk)->last()->keys()->diff($batch->keys())->isEmpty()) {
                sleep(6);
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("✓ {$missing->count()} keys translated to {$target}.json");
    }
}
