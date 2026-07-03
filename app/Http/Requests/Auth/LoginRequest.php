<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

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
            $this->recordFailedLogin();
            throw ValidationException::withMessages([
                'emp_code' => 'ข้อมูลการเข้าสู่ระบบไม่ถูกต้อง',
            ]);
        }

        if ($user->status === \App\Models\User::STATUS_RESIGN) {
            $this->recordFailedLogin();
            throw ValidationException::withMessages([
                'emp_code' => 'ข้อมูลการเข้าสู่ระบบไม่ถูกต้อง',
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
            $this->recordFailedLogin();
            throw ValidationException::withMessages([
                'emp_code' => 'ข้อมูลการเข้าสู่ระบบไม่ถูกต้อง',
            ]);
        }

        // อัปเกรดรหัสผ่าน (Rehash) เพื่อให้ Login ครั้งต่อไปเร็วขึ้นและปลอดภัยขึ้น
        // - กรณีที่ในฐานข้อมูลเป็น Plain text จะถูกเข้ารหัสทันที
        // - กรณีที่ Hash เดิมมี Cost (Rounds) สูงเกินไปจนทำให้ช้า จะถูกปรับให้เป็นค่าใหม่ตาม .env
        if (\Illuminate\Support\Facades\Hash::needsRehash($user->password) || $user->password === $this->password) {
            $user->password = \Illuminate\Support\Facades\Hash::make($this->password);
            $user->save();
        }

        Auth::login($user, $this->boolean('remember'));
        $this->clearRateLimit();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function recordFailedLogin(): void
    {
        session()->increment('login_attempts');

        $key = $this->throttleKey();
        $attemptsKey = $key . ':attempts';
        
        $attempts = Cache::increment($attemptsKey);
        
        if ($attempts == 1) {
            Cache::put($attemptsKey, 1, now()->addHour());
        }

        if ($attempts >= 5) {
            $lockKey = $key . ':lock';
            $minutes = 2;
            Cache::put($lockKey, now()->addMinutes($minutes), now()->addMinutes($minutes));
        }
    }

    public function ensureIsNotRateLimited(): void
    {
        $key = $this->throttleKey();
        $lockKey = $key . ':lock';

        if (Cache::has($lockKey)) {
            event(new Lockout($this));
            
            $lockTime = Cache::get($lockKey);
            $seconds = now()->diffInSeconds($lockTime, false);
            
            if ($seconds > 0) {
                $roundedSeconds = ceil($seconds);
                throw ValidationException::withMessages([
                    'emp_code' => "คุณเข้าสู่ระบบผิดพลาดหลายครั้ง กรุณาลองใหม่ในอีก {$roundedSeconds} วินาที",
                ]);
            }
        }
    }
    
    public function clearRateLimit(): void
    {
        session()->forget('login_attempts');
        $key = $this->throttleKey();
        Cache::forget($key . ':attempts');
        Cache::forget($key . ':lock');
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('emp_code')).'|'.$this->ip());
    }
}
