<?php


namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

abstract class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $response = new Response($validator->errors()->first(), 400);
        throw new ValidationException($validator, $response);
    }

    abstract function rules(): array;

    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            '*' => 'Malformed request.'
        ];
    }
}
