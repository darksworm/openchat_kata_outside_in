<?php


namespace App\Http\Controllers;


use App\DTO\PostCreationRequest;
use App\Exceptions\InappropriateLanguageException;
use App\Exceptions\UserDoesNotExistException;
use App\Http\Requests\CreatePostHTTPRequest;
use App\Http\Requests\GetTimelineRequest;
use App\Http\Transformers\PostTransformer;
use App\Services\PostCreationService;
use App\Services\TimelineService;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public function __construct(private PostCreationService $postCreationService,
                                private TimelineService $timelineService)
    {
    }

    public function createPost(CreatePostHTTPRequest $request): Response
    {
        $creationRequest = new PostCreationRequest(
            userId: $request->userId(),
            text: $request->text()
        );

        try {
            $post = $this->postCreationService->createPost($creationRequest);
            return response(PostTransformer::transform($post), 201);
        } catch (UserDoesNotExistException) {
            return response("User does not exist.", 400);
        } catch (InappropriateLanguageException) {
            return response("Inappropriate language used.", 400);
        }
    }

    public function getTimeline(GetTimelineRequest $request): Response
    {
        try {
            $posts = $this->timelineService->timelineForUserId($request->userId());
            return response(PostTransformer::transformAll(...$posts), 200);
        } catch (UserDoesNotExistException $e) {
            return response("User with id {$e->getUserId()} does not exist.", 400);
        }
    }
}
