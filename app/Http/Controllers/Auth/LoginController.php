<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    protected int $maxAttempts = 5; // Max login attempts
    protected int $decayMinutes = 1; // Lockout time in minutes

    public function showForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $this->checktooManyAttempts($request);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Count failed attempts
            RateLimiter::hit($this->throttleKey($request), $this->decayMinutes * 60);

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Successful login, clear failed attempts
        RateLimiter::clear($this->throttleKey($request));
        // Set session regeneration
        $request->session()->regenerate();

        // Redirect to intended page or dashboard
        if (config('auth.require_email_verification') && Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                    ->with('status', __('Please verify your email address.'));
        }
        
        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', __('Logged out successfully.'));
    }

    public function throttleKey(Request $request) : string {
        return Str::transliterate(
            Str::lower($request->input('email')).'|'.$request->ip()
        );
    }

    public function checktooManyAttempts(Request $request) : void {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), $this->maxAttempts)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => __('auth.throttle',[
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60)
            ]),
        ])->status(429);
    }
}
