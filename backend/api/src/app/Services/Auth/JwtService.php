<?php

namespace App\Services\Auth;

use App\Http\Resources\Auth\SuccessAuthResource;
use App\Http\Resources\BaseWithResponseResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

readonly class JwtService
{
    protected string $guardName;

    public function __construct()
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
}
