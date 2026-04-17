<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'emp_code' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = \App\Models\User::where('emp_code', $this->emp_code)->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'emp_code' => 'รหัสหรือ user กรอกผิดพลาด',
            ]);
        }

        if ($user->status === \App\Models\User::STATUS_RESIGN) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'emp_code' => 'รหัสหรือ user กรอกผิดพลาด',
            ]);
        }

        $isMatch = false;
        try {
            // Try standard Laravel check first
            $isMatch = \Illuminate\Support\Facades\Hash::check($this->password, $user->password);
        } catch (\RuntimeException $e) {
            // If hash check fails because it's not a bcrypt hash, check as plaintext
            $isMatch = ($this->password === $user->password);
        }

        // Final fallback if hash check didn't throw but returned false (could still be plaintext)
        if (!$isMatch) {
            $isMatch = ($this->password === $user->password);
        }

        if (!$isMatch) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'emp_code' => 'รหัสหรือ user กรอกผิดพลาด',
            ]);
        }

        Auth::login($user, $this->boolean('remember'));
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'emp_code' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('emp_code')).'|'.$this->ip());
    }
}
