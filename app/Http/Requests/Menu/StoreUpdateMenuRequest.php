<?php

namespace App\Http\Requests\Menu;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateMenuRequest extends FormRequest
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
            'id'=>['sometimes', 'required', 'exists:menus,id'],
            'title'=>['required', Rule::unique('menus')->ignore($this->id, 'id')],
            'price'=>['required', 'numeric'],
            'description'=>['required', 'max:255'],
            'image'=>['sometimes', 'required', 'image', 'dimensions:min_width=100,min_height=100', 'max:2048'],
            'products'=>['required', 'array'],
            'products.*.id'=>['sometimes', 'exists:products,id'],
            'products.*.title'=>['required', 'string'],
            'products.*.description'=>['required', 'max:255'],
            'products.*.image'=>['sometimes', 'required', 'image', 'dimensions:min_width=100,min_height=100', 'max:2048'],
            'products.*.positions'=>['array'],
            'products.*.positions.*.title'=>['required', 'string'],
            'products.*.positions.*.weight'=>['required', 'numeric'],
            'products.*.price'=>['required', 'numeric'],
            'products.*.weight'=>['required', 'numeric'],
        ];
    }

    public function messages()
    {
        return [
            'title.required'=>'Поле название обязательно для заполнения',
            'title.unique' => 'Такое название меню уже существует',
            'price.required' => 'Поле цена обязательно для заполнения',
            'description.required' => 'Поле описание обязательно для заполнения',
            'description.max' => 'Слишком длинное значение для поля описание',
            'image.required' => 'Поле картинка обязательно для заполнения',
            'image.image' => 'Поле изображение должно быть картинкой',
            'image.max' => 'Максимальный размер изображения 2Мб',
            'image.dimensions' => 'Минимальный размер изображения 100x100',
            'products.*.title.required'=>'Поле название товара обязательно для заполнения',
            'products.*.description.required' => 'Поле описание товара обязательно для заполнения',
            'products.*.description.max' => 'Слишком длинное значение для поля описание',
            'products.*.weight.required' => 'Поле вес товара обязательно для заполнения',
            'products.*.price.required' => 'Поле цена товара обязательно для заполнения',
            'products.*.image.required' => 'Поле картинка товара обязательно для заполнения',
            'products.*.image.image' => 'Поле картинка товара должно быть картинкой',
            'products.*.image.max' => 'Максимальный размер изображения 2Мб',
            'products.*.image.dimensions' => 'Минимальный размер изображения 100x100, а также изображение должно быть 1:1',
            'products.*.positions.*.title'=>'Поле название ингридиента в товаре обязательно для заполнения',
            'products.*.positions.*.weight'=>'Поле вес ингридиента в товаре обязательно для заполнения',
        ];
    }
}
