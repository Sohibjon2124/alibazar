<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class AddPromotionProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'price' => 'required|numeric|min:0',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:today'
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Продукт обязателен.',
            'product_id.integer' => 'ID продукта должен быть числом.',
            'product_id.exists' => 'Такого продукта не существует.',

            'price.required' => 'Цена обязательна.',

            'end_date.required' => 'Дата окончания обязательна.',
            'end_date.date_format' => 'Дата окончания должна быть в формате ГГГГ-ММ-ДД (например, 2025-06-10).',
            'end_date.after_or_equal' => 'Дата окончания не может быть раньше сегодняшней.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'product_id' =>  Route::current()->parameter('product'),
        ]);
    }
}
