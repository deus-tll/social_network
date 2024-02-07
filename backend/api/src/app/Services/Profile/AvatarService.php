<?php

namespace App\Services\Profile;

use App\Repositories\UserRepository;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Http\UploadedFile;

class AvatarService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly AvatarStorageService $storageService
    ){}

    /**
     * Uploads new avatar
     * @param int $user_id
     * @param UploadedFile $file
     * @return void
     */
    public function uploadAvatar(int $user_id, UploadedFile $file): void
    {
        $this->storageService->putFromContent($user_id, 'original', $file->getContent());
    }

    /**
     * Optimizes avatar to different sizes with extension ".webp"
     * @param int $user_id
     * @return void
     */
    public function optimizeAvatar(int $user_id)
    {

    }

    /**
     * Generates initial avatar(from email or random)
     * @param int $user_id
     * @return array
     */
    public function generateAvatar(int $user_id): array
    {
        $user = $this->userRepository->findOrFail($user_id);

        $email = strtolower(trim($user->email));

        return $this->getAvatars($user_id, $email);
    }

    /**
     * Gets avatars from the Gravatar using email
     * @param int $user_id
     * @param string $email
     * @return array
     */
    private function getAvatars(int $user_id, string $email): array
    {
        $url_large = $this->storeAndGetAvatarFromGravatar($user_id, $email, 'large');
        $url_medium = $this->storeAndGetAvatarFromGravatar($user_id, $email, 'medium');
        $url_small = $this->storeAndGetAvatarFromGravatar($user_id, $email, 'small');

        //info('urls', [$url_large, $url_medium, $url_small]);

        return [$url_large, $url_medium, $url_small];
    }

    private function storeAndGetAvatarFromGravatar(int $user_id, string $email, string $type): string
    {
        $url = Gravatar::get($email, $type);
        $this->storageService->putFromUrl($user_id, $type, $url);

        return $url;
    }
}
