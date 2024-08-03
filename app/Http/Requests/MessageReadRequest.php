<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class MessageReadRequest extends ApiRequest
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
            'messageId' => 'required|integer',
            'decryptionKey' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'messageId.required' => 'Message ID is required.',
            'decryptionKey.required' => 'Description Key is required.',
        ];
    }
}
