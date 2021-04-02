<?php

namespace App\Http\Requests;

class UserRegistrationRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string',
            'password' => 'required|string',
            'about' => 'required|string',
        ];
    }

    function failedValidationMessage(): string
    {
        return 'Passed post data does not match expected format';
    }
}
