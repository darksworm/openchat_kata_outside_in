<?php


namespace App\Http\Controllers;


use App\Exceptions\FollowingAlreadyExistsException;
use App\Exceptions\UserDoesNotExistException;
use App\Http\Requests\CreateFollowingHTTPRequest;
use App\Service\FollowingsService;
use Illuminate\Http\Response;

class FollowingsController extends Controller
{
    private FollowingsService $followingsService;

    public function __construct(FollowingsService $followingsService)
    {
        $this->followingsService = $followingsService;
    }

    public function createFollowing(CreateFollowingHTTPRequest $request): Response
    {
        try {
            $this->followingsService->createFollowing($request->followerId(), $request->followeeId());
        } catch (UserDoesNotExistException $e) {
            return response("User with id {$e->getUserId()} does not exist.", 400);
        } catch (FollowingAlreadyExistsException) {
            return response("Following already exists.", 400);
        }

        return response("", 201);
    }
}
