<?php

namespace App\Rules\Lotteries;

use App\Models\Lottery;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckLotteryRule implements ValidationRule
{

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $lottery = Lottery::where('id', $value)
            ->where('is_active', true)
            ->where('is_complete', false)
            ->where('free_place_count', '>', 0)
            ->where('place_count', '>', 0)
            ->first();
        if(!$lottery)
            $fail('Лотерея не найдена');
    }
}
