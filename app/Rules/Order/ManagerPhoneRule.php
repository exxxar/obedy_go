<?php

namespace App\Rules\Order;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class ManagerPhoneRule implements ValidationRule
{
    public function __construct(
        protected string $phone,
    ) {}
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phones = [preg_replace('/\D/', '', $this->phone)];
        if(Auth::check())
            $phones[] = [auth()->user()->phone];
        if(!User::where('phone', $value)->exists() || in_array($value, $phones))
            $fail('Неверный номер телефона менеджера');
    }
}
