<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TranslationService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class TranslationController extends Controller
{
    public function __construct(
        protected TranslationService $translations
    ) {}

    public function index(Request $request)
    {
        $source = $request->string('source', 'en')->toString();
        $target = $request->string('target', 'id')->toString();
        $status = $request->string('status', 'all')->toString();
        $locales = $this->translations->locales();

        if ($request->ajax()) {
            $data = $this->translations->rows($source, $target, $status);
            return datatables()->of($data)
                ->addColumn('key', fn($row) => "<code class=\"text-wrap\">{$row['key']}</code>")
                ->addColumn('source', fn($row) => "<span class=\"text-wrap\">" . e($row['sourceText'] ?? '') . "</span>")
                ->addColumn('target', function ($row) use ($target) {
                    $value = e($row['targetText'] ?? '');
                    $el = "<textarea class=\"form-control\" data-locale=\"{$target}\" data-key=\"{$row['key']}\" rows=\"2\">{$value}</textarea>";
                    return $el;
                })
                ->addColumn('status', function ($row) {
                    $status = $row['rowStatus'] ?? 'missing';
                    $color = match ($status) {
                        'translated' => 'success',
                        'identical' => 'warning',
                        default => 'danger',
                    };
                    return "<span class=\"badge bg-{$color}-subtle text-{$color}\">" . ucfirst(__($status)) . "</span>";
                })
                ->rawColumns(['key', 'source', 'target', 'status'])
                ->make(true);
        }
        
        // Ensure the source locale file exists to avoid errors in the view
        if (! in_array($source, $locales)) {
            File::ensureDirectoryExists(lang_path());
            File::put(lang_path("{$source}.json"), '{}');
            $locales[] = $source;
        }

        return view('admin.translations.index', [
            'source' => $source,
            'target' => $target,
            'status' => $status,
            'stats' => $this->translations->stats($source, $target),
            'locales' => $locales,
        ]);
    }

    public function scanMissing(Request $request): RedirectResponse
    {
        $locale = $request->string('locale', 'en')->toString();

        try {
            Artisan::call('translation:scan', [
                '--locale' => $locale,
            ]);
        } catch (RequestException $e) {
            $message = $e->response->json('error.message') ?? $e->getMessage();
            return back()->with('swal_custom_error', __('An error occurred during scanning: '. $message));
        } catch (\Exception $e) {
            return back()->with('swal_custom_error', __('An error occurred during scanning: '. $e->getMessage()));
        }

        return back()->with('success', trim(Artisan::output()) ?: __('Scan completed and missing keys updated.'));
    }

    public function translate(Request $request): RedirectResponse
    {
        $source = $request->string('source', 'en')->toString();
        $target = $request->string('target', 'id')->toString();

        try {
            Artisan::call('translation:translate', [
                'target' => $target,
                '--source' => $source,
            ]);
        } catch (RequestException $e) {
            $message = $e->response->json('error.message') ?? $e->getMessage();
            return back()->with('swal_custom_error', __('An error occurred during translation: '. $message));
        } catch (\Exception $e) {
            return back()->with('swal_custom_error', __('An error occurred during translation: '. $e->getMessage()));
        } 

        return back()->with('success', trim(Artisan::output()) ?: __('Missing keys translated successfully.'));
    }

    public function sort(Request $request): RedirectResponse
    {
        $locales = $this->translations->locales();

        foreach ($locales as $locale) {
            $this->translations->sort($locale);
        }

        return back()->with('success', __('All locale files sorted successfully.'));
    }

    public function addLocale(Request $request): RedirectResponse
    {
        $request->validate([
            'source' => ['required', 'string'],
            'target' => ['required', 'string', 'different:source'],
        ]);

        try {
            // Ensure the target locale file exists before attempting to translate
            $targetPath = lang_path("{$request->target}.json");
            if (! File::exists($targetPath)) {
                File::put($targetPath, '{}');
            }

            // Call the existing console command to add and translate the new locale
            Artisan::call('translation:translate', [
                'target' => $request->target,
                '--source' => $request->source,
                '--force' => true, // Force translation to ensure new locale is populated
            ]);
        } catch (RequestException $e) {
            $message = $e->response->json('error.message') ?? $e->getMessage();
            return back()->with('swal_custom_error', __('An error occurred while adding locale: '. $message));
        } catch (\Exception $e) {
            return back()->with('swal_custom_error', __('An error occurred while adding locale: '. $e->getMessage()));
        }

        return back()->with('success', trim(Artisan::output()) ?: __('Locale added and translations generated successfully.'));
    }

    public function saveRow(Request $request): JsonResponse
    {
        $data = $request->validate([
            'locale'    => ['required', 'string'],
            'key'       => ['required', 'string'],
            'value'     => ['nullable', 'string'],
        ]);

        $this->translations->save($data['locale'], $data['key'], $data['value'] ?? '');

        $status = blank($data['value'])
            ? 'missing'
            : ($data['value'] === $data['key'] ? 'identical' : 'translated');

        return response()->json(['success' => true, 'message' => __('Key updated successfully.'), 'rowStatus' => $status], 200);
    }
}
