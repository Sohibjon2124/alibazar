<?php

namespace App\Http\Requests\Api\V1\Orders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
        return
            [
                'order_id' => ['required', 'integer', Rule::exists('orders', 'id')->whereNull('deleted_at')],
                'delivery_type' => 'required|in:1,2',
                'delivery_address' => 'required|string',
                'status_id' => 'required|integer|exists:order_statuses,id',
                'delivery_price' => 'numeric',
                'products' => 'required|array|min:1',
                'products.*.id' => 'integer|exists:products,id',
                'products.*.count' => 'required|integer|min:0',
                'products.*.color' => 'string',
                'products.*.size' => 'string',
            ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'Поле заказа обязательно для заполнения.',
            'order_id.integer' => 'ID заказа должен быть числом.',
            'order_id.exists' => 'Заказ с указанным ID не найден или был удалён.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'order_id' =>  Route::current()->parameter('order'),
        ]);
    }
}
