<?php

namespace App\Services\Auth;

use App\Http\Resources\Auth\FailedAuthorizationResource;
use App\Http\Resources\Auth\SuccessAuthResponseResource;
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

    public function buildResponse(string|null $token, string $message, bool $isRegistration = false): SuccessAuthResponseResource|FailedAuthorizationResource
    {
        if (!$token) {
            return new FailedAuthorizationResource();
        }

        $user = $this->getUser();

        return new SuccessAuthResponseResource($user, $token, $message, $isRegistration);
    }
}
