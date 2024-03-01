<?php

namespace App\Services\Auth;

use App\Http\Resources\Auth\SuccessLoginResource;
use App\Http\Resources\Auth\SuccessRegisterResource;
use App\Http\Resources\BaseWithResponseResource;
use App\Services\Profile\AvatarService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

readonly class JwtService
{
    protected string $guardName;

    public function __construct(private AvatarService $avatarService)
    {
        $this->guardName = 'api';
    }

    public function guardApiAttempt(array $credentials): ?string
    {
        return Auth::guard($this->guardName)->attempt($credentials);
    }

    public function getUser(): ? Authenticatable
    {
        return Auth::guard($this->guardName)->user();
    }

    public function userLogout(): void
    {
        Auth::guard($this->guardName)->logout();
    }

    public function buildResponseLogin(string|null $token): BaseWithResponseResource|SuccessLoginResource
    {
        if (!$token) {
            return new BaseWithResponseResource(null, 'Unauthorized', ResponseAlias::HTTP_UNAUTHORIZED, 'failure');
        }

        $user = $this->getUser();



        $avatars = $this->avatarService->getUserAvatarUrls($user->id);

        return new SuccessLoginResource($user, $token, $avatars);
    }

    public function buildResponseRegister(string|null $token): BaseWithResponseResource|SuccessRegisterResource
    {
        if (!$token) {
            return new BaseWithResponseResource(null, 'Something went wrong while creating token for user', ResponseAlias::HTTP_UNAUTHORIZED, 'failure');
        }

        $user = $this->getUser();

        return new SuccessRegisterResource($user, $token);
    }
}
