<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CheckOrderRequest extends FormRequest
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
            'number'=>['required', 'exists:orders,number']
        ];
    }

    public function messages()
    {
        return [
            'number.required' => 'Поле номер заказа обязательно для заполнения',
            'number.exists' => 'Заказа с таким номером не существует',
        ];
    }
}
