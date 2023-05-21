<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

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
            'address' => ['required'],
            'delivery_price' => ['required', 'integer'],
            'delivery_range' => ['required'],
            'products' => ['required', 'array'],
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
            'phone' => preg_replace('/\D/', '', $this->phone)
        ]);
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле имя обязательно для заполнения',
            'phone.required' => 'Поле телефон обязательно для заполнения',
            'address.required' => 'Поле адрес обязательно для заполнения',
            'products.*' => 'Продукты в корзине некорректны'
        ];
    }

}
