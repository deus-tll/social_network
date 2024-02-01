<?php

namespace app\Services\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class JwtService
{
    protected string $guardName = 'api';

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

    public function buildResponse(string|null $token): SuccessLoginResource|FailedAuthorizationResource
    {
        if (!$token) {
            return new FailedAuthorizationResource();
        }

        $user = $this->getUser();

        return new SuccessLoginResource($user, $token);
    }
}
