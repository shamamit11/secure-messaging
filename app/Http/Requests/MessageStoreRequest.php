<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class MessageStoreRequest extends ApiRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'text' => 'required|string',
            'recipient' => 'required|email|string'
        ];
    }

    public function messages()
    {
        return [
            'text.required' => 'Message is required.',
            'recipient.required' => 'Recipient Email is required.',
        ];
    }
}
