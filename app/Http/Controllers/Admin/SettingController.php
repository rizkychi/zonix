<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Settings\GeneralSettings;
use App\Settings\MailSettings;
use App\Settings\ApiSettings;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private $validSections = [
        'general' => 'General',
        'mail' => 'Mail',
        'api' => 'API',
    ];

    public function index($section = 'general')
    {
        if (!in_array($section, array_keys($this->validSections))) {
            abort(404);
        } else {
            $settings = null;
            switch ($section) {
                case 'general':
                    $settings = app(GeneralSettings::class);
                    break;
                case 'mail':
                    $settings = app(MailSettings::class);
                    break;
                case 'api':
                    $settings = app(ApiSettings::class);
                    break;
            }
        }
        return view('admin.settings.index', compact('section', 'settings'))
            ->with('validSections', $this->validSections);
    }

    public function update(Request $request, $section)
    {
        if (!in_array($section, array_keys($this->validSections))) {
            abort(404);
        }

        switch ($section) {
            case 'general':
                $settings = app(GeneralSettings::class);
                return $this->updateGeneral($request, $settings);
            case 'mail':
                $settings = app(MailSettings::class);
                return $this->updateMail($request, $settings);
            case 'api':
                $settings = app(ApiSettings::class);
                return $this->updateApi($request, $settings);
            default:
                abort(404);
        }
    }

    private function updateGeneral(Request $request, GeneralSettings $settings)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png|max:512',
            'site_email' => 'required|email|max:255',
            'maintenance_mode' => 'boolean',
            'timezone' => 'required|string',
            'locale' => 'required|string',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
            'datetime_format' => 'required|string',
            'enable_registration' => 'boolean',
            'enable_email_verification' => 'boolean',
            'enable_recaptcha' => 'boolean',
            'recaptcha_site_key' => 'nullable|string|max:255',
            'recaptcha_secret_key' => 'nullable|string|max:255',
            'enable_google_analytics' => 'boolean',
            'google_analytics_tracking_id' => 'nullable|string|max:255',
        ]);

        $settings->site_name = $validated['site_name'];
        $settings->site_email = $validated['site_email'];
        $settings->maintenance_mode = $request->boolean('maintenance_mode');
        $settings->timezone = $validated['timezone'];
        $settings->locale = $validated['locale'];
        $settings->date_format = $validated['date_format'];
        $settings->time_format = $validated['time_format'];
        $settings->datetime_format = $validated['datetime_format'];
        $settings->enable_registration = $request->boolean('enable_registration');
        $settings->enable_email_verification = $request->boolean('enable_email_verification');
        $settings->enable_recaptcha = $request->boolean('enable_recaptcha');
        $settings->recaptcha_site_key = $validated['recaptcha_site_key'];
        $settings->recaptcha_secret_key = $validated['recaptcha_secret_key'];
        $settings->enable_google_analytics = $request->boolean('enable_google_analytics');
        $settings->google_analytics_tracking_id = $validated['google_analytics_tracking_id'];
        $settings->save();

        return redirect()->back()->with('success', __('General settings updated successfully.'));

    }

    private function updateMail(Request $request, MailSettings $settings)
    {
        $validated = $request->validate([
            'driver' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'encryption' => 'nullable|string|max:255',
            'from_address' => 'required|email|max:255',
            'from_name' => 'required|string|max:255',
        ]);

        $settings->driver = $validated['driver'];
        $settings->host = $validated['host'];
        $settings->port = $validated['port'];
        $settings->username = $validated['username'];
        $settings->password = $validated['password'];
        $settings->encryption = $validated['encryption'];
        $settings->from_address = $validated['from_address'];
        $settings->from_name = $validated['from_name'];
        $settings->save();

        return redirect()->back()->with('success', __('Mail settings updated successfully.'));
    }

    private function updateApi(Request $request, ApiSettings $settings)
    {
        $validated = $request->validate([
            'gemini_api_key' => 'nullable|string|max:255',
        ]);

        $settings->gemini_api_key = $validated['gemini_api_key'];
        $settings->save();

        return redirect()->back()->with('success', __('API settings updated successfully.'));
    }
}
