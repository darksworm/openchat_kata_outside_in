<?php

namespace Tests\Services;

use App\DTO\PostCreationRequest;
use App\Exceptions\InappropriateLanguageException;
use App\Exceptions\UserDoesNotExistException;
use App\Models\Post;
use App\Repositories\IPostRepository;
use App\Repositories\IUserRepository;
use App\Services\PostCreationService;
use Tests\TestCase;

class PostCreationServiceTest extends TestCase
{
    private IPostRepository $postRepository;
    private IUserRepository $userRepository;

    private PostCreationService $postCreationService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(IUserRepository::class);
        $this->postRepository = $this->createMock(IPostRepository::class);

        $this->postCreationService = new PostCreationService($this->userRepository, $this->postRepository);
    }

    public function
    test_throws_exception_when_nonexistent_user_id_passed()
    {
        $this->userRepository->expects($this->once())
            ->method('userWithIdExists')
            ->with('userId')
            ->willReturn(false);

        $postRequest = new PostCreationRequest('userId', 'asdfasdfas');

        $this->expectException(UserDoesNotExistException::class);
        $this->postCreationService->createPost($postRequest);
    }

    /**
     * @dataProvider inappropriateTextProvider
     */
    public function
    test_throws_exception_when_post_contains_inappropriate_language(string $inappropriateText)
    {
        $this->userRepository->expects($this->once())
            ->method('userWithIdExists')
            ->with('userId')
            ->willReturn(true);

        $postRequest = new PostCreationRequest('userId', $inappropriateText);

        $this->expectException(InappropriateLanguageException::class);
        $this->postCreationService->createPost($postRequest);
    }

    public function
    test_creates_post()
    {
        $post = new Post();
        $post->userId = "user-id";
        $post->text = "text";

        $this->userRepository->expects($this->once())
            ->method('userWithIdExists')
            ->with($post->userId)
            ->willReturn(true);

        $this->postRepository->expects($this->once())
            ->method('createPost')
            ->with($post->userId, $post->text)
            ->willReturn($post);

        $postRequest = new PostCreationRequest($post->userId, $post->text);
        $createdPost = $this->postCreationService->createPost($postRequest);
        $this->assertEquals($post, $createdPost);
    }

    public function inappropriateTextProvider(): array
    {
        return [
            ['ice cream'],
            ['elephant'],
            ['orange'],
            ['ORANGE'],
            ['ELEphaNT'],
            ['eleICe cREamphant'],
            ['I like to eat oranges every day']
        ];
    }
}
