<?php


namespace App\Http\Controllers;


use App\Http\Requests\LoginHTTPRequest;
use App\Service\LoginFailException;
use App\Service\LoginService;

class LoginController extends Controller
{
    private LoginService $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function loginUser(LoginHTTPRequest $request) {
        try {
            $user = $this->loginService->loginUser($request->username(), $request->password());

            return response([
                'userId' => $user->user_id,
                'username' => $user->username,
                'about' => $user->about,
            ]);
        } catch (LoginFailException) {
            return response('Invalid credentials.', 400);
        }
    }
}
