<?php


namespace App\Http\Requests;


class GetFolloweesHTTPRequest extends BaseRequest
{
    public function followerId(): string
    {
        return $this->route('followerId');
    }

    public function rules(): array
    {
        return [
            'followerId' => 'required|uuid'
        ];
    }

    public function messages(): array
    {
        return parent::messages() + [
                'followerId.*' => 'Incorrect follower id.'
            ];
    }
}
