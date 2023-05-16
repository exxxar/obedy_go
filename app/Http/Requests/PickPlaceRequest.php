<?php

namespace App\Http\Requests;

use App\Rules\Lotteries\CheckLotteryPlaceRule;
use App\Rules\Lotteries\CheckLotteryRule;
use App\Rules\Lotteries\CheckPromocodeRule;
use Illuminate\Foundation\Http\FormRequest;

class PickPlaceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', new CheckPromocodeRule()],
            'name' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'email'],
            'lottery_id' => ['required', 'integer', new CheckLotteryRule()],
            'selected_place' => ['required', 'integer', new CheckLotteryPlaceRule($this->request->get('lottery_id'))]
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Поле промокод обязательно для заполнения',
            'name.required' => 'Поле имя обязательно для заполнения',
            'phone.required' => 'Поле телефон обязательно для заполнения',
            'email.required' => 'Поле почта обязательно для заполнения',
            'email.email' => 'Поле почтадолжно быть действительным почтовым адресом',
        ];
    }
}
