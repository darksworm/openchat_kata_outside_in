<?php


namespace App\Http\Controllers;


use App\DTO\UserRegistrationRequest;
use App\Http\Requests\UserRegistrationHTTPRequest;
use App\Service\DuplicateUsernameException;
use App\Service\RegistrationService;
use Illuminate\Http\Response;

class RegistrationController extends Controller
{
    private RegistrationService $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function registerUser(UserRegistrationHTTPRequest $request): Response
    {
        $request = new UserRegistrationRequest(
            $request->get('username'),
            $request->get('password'),
            $request->get('about'),
        );

        try {
            $user = $this->registrationService->registerUser($request);

            return response([
                'userId' => $user->user_id,
                'username' => $user->username,
                'about' => $user->about,
            ], 201);
        } catch (DuplicateUsernameException) {
            return response("Username already in use.", 400);
        }
    }
}
