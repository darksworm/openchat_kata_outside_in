<?php


namespace App\Http\Controllers;


use App\Http\Transformers\UserTransformer;
use App\Services\UserService;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function getAllUsers(): Response
    {
        $allUsers = $this->userService->getAllUsers();
        $transformed = UserTransformer::transformAll(...$allUsers);
        return response($transformed);
    }
}
