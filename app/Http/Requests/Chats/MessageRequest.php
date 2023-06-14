<?php

namespace App\Http\Requests\Chats;

use App\Rules\Chat\ChatRule;
use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            'chatId'=>['required', new ChatRule()],
            'message'=>['required_without:files'],
            'files' => ['required_without:message']
        ];
    }
}
