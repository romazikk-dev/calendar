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
    protected $guard_key = 'user';
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->has('user'))
            return [
                'user_email' => 'required|string|email',
                'user_password' => 'required|string',
            ];
        if($this->has('worker'))
            return [
                'worker_email' => 'required|string|email',
                'worker_password' => 'required|string',
            ];
    }
    
    /**
     * Gets right credentials.
     *
     * @return array
     */
    public function getCredentials()
    {
        $credentials = $this->only($this->guard_key . '_email', $this->guard_key . '_password');
        // dd($credentials);
        return [
            'email' => $credentials[$this->guard_key . '_email'],
            'password' => $credentials[$this->guard_key . '_password']
        ];
    }
    
    /**
     * Sets guard key.
     *
     * @return void
     */
    public function setGuardKey()
    {
        if($this->has('worker')){
            $this->guard_key = 'worker';
        }else{
            $this->guard_key = 'user';
        }
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->setGuardKey();
        $this->ensureIsNotRateLimited();
        
        $credentials = $this->getCredentials();

        if (
            ($this->has('user') && !Auth::attempt($credentials, $this->filled($this->guard_key . '_remember'))) ||
            ($this->has('worker') && !Auth::guard('worker')->attempt($credentials, $this->filled($this->guard_key . '_remember')))
        ) {
            // RateLimiter::hit($this->throttleKey());
            
            // dd([
            //     $this->guard_key . '_email' => __('auth.failed'),
            // ]);
            
            throw ValidationException::withMessages([
                $this->guard_key . '_email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}
