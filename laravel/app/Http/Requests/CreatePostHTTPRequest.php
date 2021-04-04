<?php


namespace App\Http\Requests;


class CreatePostHTTPRequest extends BaseRequest
{
    public function userId()
    {
        return $this->route('userId');
    }

    public function text()
    {
        return $this->get('text');
    }

    public function rules(): array
    {
        return [
            'userId' => static::REQUIRED_UUID,
            'text' => static::REQUIRED_STRING
        ];
    }

    public function messages(): array
    {
        return parent::messages() + [
                'userId.*' => 'Invalid user id.'
        ];
    }
}
