<?php

namespace App\Rules\Lotteries;

use App\Models\Lottery;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckLotteryPlaceRule implements ValidationRule
{

    public function __construct(
        protected string $lotteryId,
    ) {}
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $lottery = Lottery::find($this->lotteryId);
        if (in_array($value, $lottery->occupied_places))
            $fail('Данное поле уже занято');

    }
}
