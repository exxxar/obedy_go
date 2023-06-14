<?php

namespace App\Rules\Chat;

use App\Models\Chat;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ChatRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $chat = Chat::where('user_id', auth()->id())
            ->orWhere('specialist_id', auth()->id())
            ->where('id', $value)
            ->first();
        if(!$chat)
            $fail('Данный чат не найден!');
    }
}
