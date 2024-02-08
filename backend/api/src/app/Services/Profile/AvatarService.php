<?php

namespace App\Services\Profile;

use App\Repositories\UserRepository;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

readonly class AvatarService
{
    public function __construct(
        private UserRepository       $userRepository,
        private AvatarStorageService $storageService
    ){}

    /**
     * Uploads new avatar
     * @param int $userId
     * @param UploadedFile $file
     * @return void
     */
    public function uploadAvatar(int $userId, UploadedFile $file): void
    {
        $extension = $file->getClientOriginalExtension();

        $this->storageService->putAvatar($userId, 'original', $file->getContent(), $extension);
    }

    /**
     * Optimizes avatar to different sizes with extension ".webp"
     * @param int $userId
     * @return array
     */
    public function optimizeAvatar(int $userId): array
    {
        $avatarSizes = config('gravatar');

        $avatars = [];

        foreach ($avatarSizes as $type => $config) {
            if ($type === 'default') {
                continue;
            }

            $size = $config['size'];

            $fileDir = '/' . $userId . '/';

            $files = Storage::disk(env('AVATAR_DISK'))->files($fileDir);

            $originalFile = collect($files)->first(function ($file) {
                return Str::startsWith(pathinfo($file, PATHINFO_BASENAME), 'original');
            });

            if($originalFile) {
                $fileContent = Storage::disk(env('AVATAR_DISK'))->get($originalFile);

                $manager = new ImageManager(new Driver());
                $image = $manager->read($fileContent);

                $image->scale($size, $size);

                $avatars['url_' . $type] = $this->storageService->putAvatar($userId, $type, $image->toWebp());
            }
        }

        return $avatars;
    }



    /**
     * Generates initial avatar(from email or random)
     * @param int $userId
     * @return array
     */
    public function generateAvatar(int $userId): array
    {
        $user = $this->userRepository->findOrFail($userId);

        $email = strtolower(trim($user->email));

        return $this->getAvatars($userId, $email);
    }

    /**
     * Gets avatars from the Gravatar using email
     * @param int $userId
     * @param string $email
     * @return array
     */
    private function getAvatars(int $userId, string $email): array
    {
        $urlLarge = $this->storeAndGetAvatarFromGravatar($userId, $email, 'large');
        $urlMedium = $this->storeAndGetAvatarFromGravatar($userId, $email, 'medium');
        $urlSmall = $this->storeAndGetAvatarFromGravatar($userId, $email, 'small');

        return [
            'url_large' => $urlLarge,
            'url_medium' => $urlMedium,
            'url_small' => $urlSmall
        ];
    }

    private function storeAndGetAvatarFromGravatar(int $userId, string $email, string $type): string
    {
        $url = Gravatar::get($email, $type);
        $imageContent = file_get_contents($url);

        return $this->storageService->putAvatar($userId, $type, $imageContent);
    }
}
