<?php


namespace App\Http\Controllers;


use App\DTO\PostCreationRequest;
use App\Exceptions\InappropriateLanguageException;
use App\Exceptions\UserDoesNotExistException;
use App\Http\Requests\CreatePostHTTPRequest;
use App\Http\Transformers\PostTransformer;
use App\Service\PostCreationService;
use Illuminate\Http\Response;

class PostController extends Controller
{
    private PostCreationService $postCreationService;

    public function __construct(PostCreationService $postCreationService)
    {
        $this->postCreationService = $postCreationService;
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
}
