<?php


namespace App\Http\Requests;


class GetWallRequest extends BaseRequest
{
    public function userId(): string
    {
        return $this->route('userId');
    }

    function rules(): array
    {
        return [
            'userId' => static::REQUIRED_UUID
        ];
    }

    function messages(): array
    {
        return parent::messages() + [
                'userId.*' => 'Invalid user id.'
            ];
    }
}
