<?php


namespace App\Http\Requests;


class LoginHTTPRequest extends BaseRequest
{
    public function username()
    {
        return $this->get('username');
    }

    public function password()
    {
        return $this->get('password');
    }

    public function rules(): array
    {
        return [
            'username' => static::REQUIRED_STRING,
            'password' => static::REQUIRED_STRING
        ];
    }
}
