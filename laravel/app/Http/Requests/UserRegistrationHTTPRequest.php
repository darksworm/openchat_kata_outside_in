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

    public function rules(): array
    {
        return [
            'username' => static::REQUIRED_STRING,
            'password' => static::REQUIRED_STRING,
            'about' => static::REQUIRED_STRING,
        ];
    }

    public function messages(): array
    {
        return [
            '*' => 'Passed post data does not match expected format'
        ];
    }
}
