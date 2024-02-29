<?php

namespace App\Services\Auth;

use App\Http\Resources\Auth\SuccessAuthResource;
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

    public function __construct(private readonly AvatarService $avatarService)
    {
        $this->guardName = 'api';
    }

    public function guardApiAttempt(array $credentials): ?string
    {
        return Auth::guard($this->guardName)->attempt($credentials);
    }

    public function guardApiRefresh(): ?string
    {
        return Auth::guard($this->guardName)->refresh();
    }

    public function getUser(): ? Authenticatable
    {
        return Auth::guard('api')->user();
    }

    public function userLogout(): void
    {
        Auth::guard('api')->logout();
    }

    public function buildResponse(string|null $token, string $message, bool $isRegistration = false): SuccessAuthResource|BaseWithResponseResource
    {
        if (!$token) {
            return new BaseWithResponseResource(null, 'Unauthorized', ResponseAlias::HTTP_UNAUTHORIZED, 'failure');
        }

        $user = $this->getUser();

        return new SuccessAuthResource($user, $token, $message, $isRegistration);
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
