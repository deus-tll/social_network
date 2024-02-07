<?php

namespace App\Services\Profile;

use Illuminate\Support\Facades\Storage;

class AvatarStorageService
{
    public function putFromUrl(int $user_id, string $avatar_type, string $avatar_url): void
    {
        $imageContent = file_get_contents($avatar_url);

        $path = $this->getAvatarPath($user_id, $avatar_type);

        Storage::disk('avatars_local')->put($path, $imageContent);
    }

    public function putFromContent(int $user_id, string $avatar_type, string $avatar_content): void
    {
        $path = $this->getAvatarPath($user_id, $avatar_type);

        Storage::disk('avatars_local')->put($path, $avatar_content);
    }

    private function getAvatarPath(int $user_id, string $avatar_type): string
    {
        return $user_id . '/' . $avatar_type . '.webp';
    }
}
