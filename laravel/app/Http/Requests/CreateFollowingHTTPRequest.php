<?php


namespace App\Http\Requests;


class CreateFollowingHTTPRequest extends BaseRequest
{
    function followerId(): string
    {
        return $this->get('followerId');
    }

    function followeeId(): string
    {
        return $this->get('followeeId');
    }

    function rules(): array
    {
        return [
            'followerId' => 'required|uuid',
            'followeeId' => 'required|uuid|different:followerId',
        ];
    }

    function messages(): array
    {
        return parent::messages() + [
                'followeeId.different' => 'You cannot follow yourself.'
            ];
    }
}
