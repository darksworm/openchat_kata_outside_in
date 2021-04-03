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
            'username' => 'required|string',
            'password' => 'required|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            'username.required' => 'Malformed request.',
            'password.required' => 'Malformed request.'
        ];
    }
}
