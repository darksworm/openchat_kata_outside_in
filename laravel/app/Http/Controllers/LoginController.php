<?php


namespace App\Http\Controllers;


use App\Http\Requests\LoginHTTPRequest;
use App\Service\LoginService;

class LoginController extends Controller
{
    private LoginService $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function loginUser(LoginHTTPRequest $request) {

    }
}
