<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\BadRequestResource;
use App\Http\Resources\Auth\FailedAuthorizationResource;
use App\Http\Resources\Auth\SuccessAuthResponseResource;
use App\Services\Auth\JwtService;
use App\Services\Auth\UserService;
use OpenApi\Attributes as OAT;

#[OAT\Post(
    path: '/api/auth/register',
    operationId: 'api.auth.register',
    summary: 'Register a user',
    requestBody: new OAT\RequestBody(
        required: true,
        content: new OAT\JsonContent(ref: '#/components/schemas/RegisterRequest')
    ),
    tags: ['auth'],
    responses: [
        new OAT\Response(
            response: 201,
            description: 'Registration was successful',
            content: new OAT\JsonContent(ref: '#/components/schemas/SuccessAuthResponseResource')
        ),
        new OAT\Response(
            response: 400,
            description: 'Password confirmation failed',
            content: new OAT\JsonContent(ref: '#/components/schemas/BadRequestResource')
        ),
    ]
)]
class RegisterController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly JwtService  $jwtService,
    ){}

    public function __invoke(RegisterRequest $request): BadRequestResource|FailedAuthorizationResource|SuccessAuthResponseResource
    {
        if ($request->input('password') != $request->input('password_confirmation')){
            return new BadRequestResource("The passwords don't match");
        }

        $this->userService->create($request->validated());

        $token = $this->jwtService->guardApiAttempt($request->only('email', 'password'));

        return $this->jwtService->buildResponse($token, 'Registration was successful', true);
    }
}
