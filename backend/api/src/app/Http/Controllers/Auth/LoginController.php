<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\FailedAuthorizationResource;
use App\Http\Resources\Auth\SuccessLoginResource;
use App\Services\Auth\JwtService;
use OpenApi\Attributes as OAT;

#[OAT\Post(
    path: '/api/auth/login',
    operationId: 'api.auth.login',
    summary: 'Login a user',
    requestBody: new OAT\RequestBody(
        required: true,
        content: new OAT\JsonContent(ref: '#/components/schemas/LoginRequest')
    ),
    tags: ['auth'],
    responses: [
        new OAT\Response(
            response: 200,
            description: 'User logged in successfully',
            content: new OAT\JsonContent(ref: '#/components/schemas/SuccessLoginResource')
        )]
)]
class LoginController extends Controller
{
    public function __construct(
        private readonly JwtService  $jwtService,
    ){}

    public function __invoke(LoginRequest $request): FailedAuthorizationResource|SuccessLoginResource
    {
        $token = $this->jwtService->guardApiAttempt($request->validated());

        return $this->jwtService->buildResponse($token, 'User logged in successfully');
    }
}
