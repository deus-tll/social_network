<?php

namespace App\Services\Profile;

use Illuminate\Support\Facades\Storage;

readonly class AvatarStorageService
{
    public function putAvatar(int $userId, string $avatarType, string $avatar, string $extension = 'webp'): string
    {
        $path = $this->getAvatarPath($userId, $avatarType, $extension);

        Storage::disk(env('AVATAR_DISK'))->put($path, $avatar);

        return Storage::disk(env('AVATAR_DISK'))->url($path);
    }

    private function getAvatarPath(int $userId, string $avatarType, string $extension): string
    {
        return $userId . '/' . $avatarType . '.' . $extension;
    }
}
