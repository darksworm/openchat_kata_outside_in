<?php


namespace App\Http\Controllers;


use App\DTO\UserRegistrationRequest;
use App\Exceptions\DuplicateUsernameException;
use App\Exceptions\Login\LoginFailException;
use App\Http\Requests\LoginHTTPRequest;
use App\Http\Requests\UserRegistrationHTTPRequest;
use App\Http\Transformers\UserTransformer;
use App\Services\LoginService;
use App\Services\RegistrationService;
use Illuminate\Http\Response;

class AuthorizationController
{
    private LoginService $loginService;
    private RegistrationService $registrationService;

    public function __construct(LoginService $loginService, RegistrationService $registrationService)
    {
        $this->loginService = $loginService;
        $this->registrationService = $registrationService;
    }

    public function loginUser(LoginHTTPRequest $request): Response
    {
        try {
            $user = $this->loginService->loginUser($request->username(), $request->password());
            return response(UserTransformer::transform($user));
        } catch (LoginFailException) {
            return response('Invalid credentials.', 400);
        }
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
