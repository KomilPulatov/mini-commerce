<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        //
    }

    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                'integer',
                'min:1',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator) {
                if (! $this->header('Idempotency-Key')) {
                    $validator->errors()->add(
                        'idempotency_key',
                        'The Idempotency-Key header is required.'
                    );
                }
            },
        ];
    }
}
