<?php


namespace App\Http\Requests;


class CreateFollowingHTTPRequest extends BaseRequest
{
    public function followerId(): string
    {
        return $this->get('followerId');
    }

    public function followeeId(): string
    {
        return $this->get('followeeId');
    }

    public function rules(): array
    {
        return [
            'followerId' => static::REQUIRED_UUID,
            'followeeId' => static::REQUIRED_UUID . '|different:followerId',
        ];
    }

    public function messages(): array
    {
        return parent::messages() + [
                'followeeId.different' => 'You cannot follow yourself.'
            ];
    }
}
