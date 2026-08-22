<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'min:1',
            ],

            'type' => [
                'required',
                'string',
                'max:100',
            ],

            'channel' => [
                'required',
                Rule::in([
                    'email',
                    'sms',
                    'push',
                ]),
            ],

            'subject' => [
                'nullable',
                'string',
                'max:255',
            ],

            'message' => [
                'required',
                'string',
            ],

            'reference_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'reference_id' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ];
    }
}
