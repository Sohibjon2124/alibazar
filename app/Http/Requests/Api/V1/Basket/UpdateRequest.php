<?php

namespace App\Http\Requests\Api\V1\Basket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

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
        return [
            'id' => 'required|integer|exists:baskets,id',
            'count' => 'required|integer',
            'color' => 'string',
            'size' => 'string'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id' => Route::current()->parameter('basket'),
        ]);
    }
}
