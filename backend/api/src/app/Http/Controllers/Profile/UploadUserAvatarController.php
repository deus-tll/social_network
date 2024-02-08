<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UploadUserAvatarRequest;
use App\Jobs\Profile\OptimizeAvatarJob;
use App\Services\Profile\AvatarService;

class UploadUserAvatarController extends Controller
{
    function __construct(){}

    public function __invoke(UploadUserAvatarRequest $request, AvatarService $avatarService): void
    {
        $userId = $request->user()->id;
        $avatar = $request->file('avatar');

        $avatarService->uploadAvatar($userId, $avatar);
        OptimizeAvatarJob::dispatch($userId)
            ->onQueue('avatars.jobs');
    }
}
