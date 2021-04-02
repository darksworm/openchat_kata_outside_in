<?php

namespace App\Http\Requests;

class UserRegistrationHTTPRequest extends BaseRequest
{
    public function username()
    {
        return $this->get('username');
    }

    public function password()
    {
        return $this->get('password');
    }

    public function about()
    {
        return $this->get('about');
    }

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

    public function failedValidationMessage(): string
    {
        return 'Passed post data does not match expected format';
    }
}
