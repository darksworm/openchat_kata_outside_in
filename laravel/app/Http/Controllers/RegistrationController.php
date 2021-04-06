<?php


namespace App\Http\Controllers;


use App\DTO\UserRegistrationRequest;
use App\Exceptions\DuplicateUsernameException;
use App\Http\Requests\UserRegistrationHTTPRequest;
use App\Http\Transformers\UserTransformer;
use App\Services\RegistrationService;
use Illuminate\Http\Response;

class RegistrationController
{
    public function __construct(
        private RegistrationService $registrationService
    )
    {
    }

    public function registerUser(UserRegistrationHTTPRequest $request): Response
    {
        $creationRequest = $this->HTTPRequestToServiceRequest($request);

        try {
            $user = $this->registrationService->registerUser($creationRequest);
            return response(UserTransformer::transform($user), 201);
        } catch (DuplicateUsernameException) {
            return response("Username already in use.", 400);
        }
    }

    private function HTTPRequestToServiceRequest(UserRegistrationHTTPRequest $request): UserRegistrationRequest
    {
        return new UserRegistrationRequest(
            username: $request->username(),
            password: $request->password(),
            about: $request->about()
        );
    }
}
