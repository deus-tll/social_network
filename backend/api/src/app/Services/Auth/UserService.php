<?php

namespace App\Services\Auth;

use App\Jobs\Profile\GenerateInitialAvatarJob;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository){}

    public function create(mixed $data): Model|Builder
    {
        $user = $this->userRepository->create($data);

        info('RegisterController | user_id:', [$user->id]);

        GenerateInitialAvatarJob::dispatch($user->id)
            ->onQueue('avatars.jobs');

        return $user;
    }
}
