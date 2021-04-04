<?php


namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

abstract class BaseRequest extends FormRequest
{
    protected const REQUIRED_STRING = 'required|string';
    protected const REQUIRED_UUID = 'required|uuid';

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

    public function all($keys = null): array
    {
        $routeParams = parent::route()->parameters();

        if (null !== $keys) {
            $routeParams = collect($routeParams)->only($keys);
        }

        return parent::all($keys) + $routeParams;
    }

    public function messages(): array
    {
        return [
            '*' => 'Malformed request.'
        ];
    }
}
