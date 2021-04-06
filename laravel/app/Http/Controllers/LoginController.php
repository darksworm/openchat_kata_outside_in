<?php


namespace App\Http\Controllers;


use App\Exceptions\Login\LoginFailException;
use App\Http\Requests\LoginHTTPRequest;
use App\Http\Transformers\UserTransformer;
use App\Services\LoginService;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    public function __construct(
        private LoginService $loginService
    )
    {
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

}
