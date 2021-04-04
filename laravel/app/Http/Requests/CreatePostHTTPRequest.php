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
            'userId' => 'required|uuid',
            'text' => 'required|string'
        ];
    }

    public function all($keys = null): array
    {
        return array_merge(
            parent::all($keys),
            ['userId' => $this->route('userId')]
        );
    }

    public function messages(): array
    {
        return parent::messages() + [
            'userId.required' => 'Invalid user id.',
            'userId.uuid' => 'Invalid user id.',
        ];
    }
}
