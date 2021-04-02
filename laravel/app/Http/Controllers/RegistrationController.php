<?php


namespace App\Http\Controllers;


use App\Http\Requests\UserRegistrationRequest;
use Illuminate\Http\Response;

class RegistrationController extends Controller
{
    public function registerUser(UserRegistrationRequest $request): Response
    {
        return response("", 201);
    }
}
