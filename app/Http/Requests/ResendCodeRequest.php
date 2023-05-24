<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResendCodeRequest extends FormRequest
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
            'phone'=>['required', 'exists:users,phone']
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
            'phone.required' => 'Поле телефон обязательно для заполнения',
            'phone.exists'=>'Не существует аккаунта с таким номером телефона'
        ];
    }
}
