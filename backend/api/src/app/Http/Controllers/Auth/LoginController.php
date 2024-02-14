<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\SuccessAuthResource;
use App\Http\Resources\BaseWithResponseResource;
use App\Http\Resources\Errors\InternalServerErrorResource;
use App\Services\Auth\JwtService;
use Exception;
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
            content: new OAT\JsonContent(ref: '#/components/schemas/SuccessAuthResource')
        ),
        new OAT\Response(
            response: 401,
            description: 'Unauthorized attempt to get access',
            content: new OAT\JsonContent(
                properties: [
                    new OAT\Property(
                        property: 'status',
                        type: 'string',
                        example: 'failure'
                    ),
                    new OAT\Property(
                        property: 'message',
                        type: 'string',
                        example: "Unauthorized"
                    ),
                ]
            )
        ),
        new OAT\Response(
            response: 500,
            description: 'Some internal server error occurred',
            content: new OAT\JsonContent(ref: '#/components/schemas/InternalServerErrorResource')
        )]
)]
class LoginController extends Controller
{
    public function __construct(
        private readonly JwtService  $jwtService,
    ){}

    public function __invoke(LoginRequest $request): SuccessAuthResource|BaseWithResponseResource|InternalServerErrorResource
    {
        try {
            $token = $this->jwtService->guardApiAttempt($request->validated());

            return $this->jwtService->buildResponse($token, 'User logged in successfully');
        }
        catch (Exception $e) {
            return new InternalServerErrorResource(['error' => $e->getMessage()]);
        }
    }
}
