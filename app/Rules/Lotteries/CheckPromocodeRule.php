<?php

namespace App\Rules\Lotteries;

use App\Models\LotteryPromocode;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckPromocodeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $promocode = LotteryPromocode::where('code', $value)
            ->where('max_activation_count', '>', 0)
            ->first();
        if(!$promocode) {
            $fail('Данный Код не найден!');
        }else{
            if ($promocode->max_activation_count == $promocode->current_activation_count)
                $fail('Данный Код уже использован');
        }
    }

}
