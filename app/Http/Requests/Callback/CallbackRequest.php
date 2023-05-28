<?php

namespace App\Http\Requests\Callback;

use Illuminate\Foundation\Http\FormRequest;

class CallbackRequest extends FormRequest
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
            'name' => ['required'],
            'phone' => ['required'],
            'address' => ['required_if:typeValue,==,0'],
            'message' => ['sometimes', 'nullable', 'required_without:files'],
            'files' => ['sometimes', 'nullable', 'required_without:message']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле имя обязательно для заполнения',
            'phone.required' => 'Поле телефон обязательно для заполнения',
            'address.required_if' => 'Поле адрес обязательно для заполнения при оформлении заказа',
            'message.required_without' => 'Поле сообщение обязательно для заполнения, если нет аудиосообщения',
            'files.required_without' => 'Запись аудиосообщения обязательна, если незаполнено поле сообщение',
        ];
    }
}
