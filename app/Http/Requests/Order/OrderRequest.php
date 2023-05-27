<?php

namespace App\Http\Requests\Order;

use App\Rules\Order\ManagerPhoneRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
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
            'phone' => ['required'],
            'name' => ['required'],
            'address' => ['required_without:manager_phone'],
            'delivery_price' => ['required_without:manager_phone', 'nullable', 'integer'],
            'delivery_range' => ['required_without:manager_phone','nullable'],
            'products' => ['required', 'array'],
            'code'=>['sometimes', 'nullable', Rule::exists('password_reset_tokens', 'token')
                ->where('phone', $this->phone), 'max:4', 'min:4'],
            'manager_phone' => ['sometimes', 'nullable', 'required_without:address',
                new ManagerPhoneRule($this->request->get('phone'))],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'phone' => preg_replace('/\D/', '', $this->phone),
            'manager_phone' => preg_replace('/\D/', '', $this->manager_phone)
        ]);
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле имя обязательно для заполнения',
            'phone.required' => 'Поле телефон обязательно для заполнения',
            'address.required_without' => 'Поле адрес обязательно для заполнения, если не заполнен номер менеджера',
            'products.*' => 'Продукты в корзине некорректны',
            'code.required' => 'Поле код подтверждения обязательно для заполнения',
            'code.exists'=>'Неверный код подтверждения',
            'code.max'=>'Код подтверждения должен содержать 4 цифры',
            'code.min'=>'Код подтверждения должен содержать 4 цифры',
            'manager_phone.required_without' => 'Поле телефон менеджера обязательно для заполнения, если не заполнен адрес',
        ];
    }

}
