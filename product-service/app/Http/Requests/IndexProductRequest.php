<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'string', 'max:255'],

            'min_price' => ['sometimes', 'integer', 'min:0'],

            'max_price' => [
                'sometimes',
                'integer',
                'min:0',
                'gte:min_price',
            ],

            'is_active' => ['sometimes', 'boolean'],

            'sort' => [
                'sometimes',
                Rule::in([
                    'name',
                    'price',
                    'stock',
                    'created_at',
                ]),
            ],

            'direction' => [
                'sometimes',
                Rule::in(['asc', 'desc']),
            ],

            'per_page' => [
                'sometimes',
                'integer',
                'min:1',
                'max:100',
            ],
        ];
    }
}
