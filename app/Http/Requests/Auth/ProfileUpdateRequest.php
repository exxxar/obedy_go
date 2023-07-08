<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class ProfileUpdateRequest extends FormRequest
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
            'name'=>['required', 'string'],
            'phone'=>['required', 'string', 'min:11', 'max:11', 'unique:users,phone,'.auth()->id()],
            'addresses'=>[ 'array'],
            'description'=>[new RequiredIf(auth()->user()->hasRole('specialist')), 'string'],
            'password'=>['nullable', 'min:8']
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'Поле имя обязательно для заполнения',
            'phone.required' => 'Поле телефон обязательно для заполнения',
            'phone.unique' => 'Аккаунт с таким номером телефона уже существует',
            'phone.min' => 'Неверный формат телефона',
            'phone.max' => 'Неверный формат телефона',
            'password.min' => 'Минимальное количество символов для поля пароль - 8',
            'addresses.required' => 'Поле адреса доставки обязательно для заполнения',
            'description.required' => 'Поле описание обязательно для заполнения'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => preg_replace('/\D/', '', $this->phone)
        ]);
    }
}
