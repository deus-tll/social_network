<?php

namespace App\Services\Auth;

use App\Jobs\Profile\GenerateInitialAvatarJob;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

readonly class UserService
{
    public function __construct(private UserRepository $userRepository, private EmailVerificationService $emailVerificationService){}

    public function create(mixed $data): Model|Builder
    {
        $user = $this->userRepository->create($data);

        $this->emailVerificationService->sendVerificationLink($user);

        GenerateInitialAvatarJob::dispatch($user->id)
            ->onQueue('avatars.jobs');

        return $user;
    }
}
