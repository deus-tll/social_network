<?php

namespace App\Services\Auth;

use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository){}

    public function create(mixed $data): Model|Builder
    {
        return $this->userRepository->create($data);
    }
}
