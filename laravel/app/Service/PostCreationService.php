<?php


namespace App\Service;


use App\DTO\PostCreationRequest;
use App\Exceptions\InappropriateLanguageException;
use App\Exceptions\UserDoesNotExistException;
use App\Repository\IPostRepository;
use App\Repository\IUserRepository;

class PostCreationService
{
    private IUserRepository $userRepository;
    private IPostRepository $postRepository;

    public function __construct(IUserRepository $userRepository, IPostRepository $postRepository)
    {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
    }

    public function createPost(PostCreationRequest $postRequest)
    {
        if (false === $this->userRepository->userWithIdExists($postRequest->getUserId())) {
            throw new UserDoesNotExistException();
        }

        if (false === $this->isLanguageAppropriate($postRequest->getText())) {
            throw new InappropriateLanguageException();
        }

        return $this->postRepository->createPost($postRequest->getUserId(), $postRequest->getText());
    }

    private function isLanguageAppropriate(string $text): bool
    {
        $blacklist = collect(['ice cream', 'elephant', 'orange']);
        $lowerText = strtolower($text);

        return false === $blacklist->contains(function ($badWord) use ($lowerText) {
                return str_contains($lowerText, $badWord);
            });
    }
}
