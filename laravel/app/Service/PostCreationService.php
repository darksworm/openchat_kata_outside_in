<?php


namespace App\Service;


use App\DTO\PostCreationRequest;
use App\Exceptions\InappropriateLanguageException;
use App\Exceptions\UserDoesNotExistException;
use App\Models\Post;
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

    /**
     * @throws UserDoesNotExistException
     * @throws InappropriateLanguageException
     */
    public function createPost(PostCreationRequest $postRequest): Post
    {
        $this->validateUserExists($postRequest->getUserId());
        $this->validateLanguageAppropriate($postRequest->getText());

        return $this->postRepository->createPost(
            $postRequest->getUserId(),
            $postRequest->getText()
        );
    }

    /**
     * @throws UserDoesNotExistException
     */
    private function validateUserExists(string $userId): void
    {
        if (false === $this->userRepository->userWithIdExists($userId)) {
            throw new UserDoesNotExistException($userId);
        }
    }

    /**
     * @throws InappropriateLanguageException
     */
    private function validateLanguageAppropriate(string $text): void
    {
        if (false === $this->isLanguageAppropriate($text)) {
            throw new InappropriateLanguageException();
        }
    }

    private function isLanguageAppropriate(string $text): bool
    {
        $blacklist = collect(['ice cream', 'elephant', 'orange']);
        $lowerText = strtolower($text);

        $containsBadWords = $blacklist->contains(fn($w) => str_contains($lowerText, $w));
        return false === $containsBadWords;
    }
}
