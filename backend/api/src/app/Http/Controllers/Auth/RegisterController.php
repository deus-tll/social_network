<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\SuccessAuthResource;
use App\Http\Resources\BaseWithResponseResource;
use App\Http\Resources\Errors\InternalServerErrorResource;
use App\Services\Auth\JwtService;
use App\Services\Auth\UserService;
use Exception;
use OpenApi\Attributes as OAT;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
            content: new OAT\JsonContent(ref: '#/components/schemas/SuccessAuthResource')
        ),
        new OAT\Response(
            response: 400,
            description: "The passwords don't match",
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
                        example: "The passwords don't match"
                    ),
                ]
            )
        ),
        new OAT\Response(
            response: 500,
            description: 'Some internal server error occurred',
            content: new OAT\JsonContent(ref: '#/components/schemas/InternalServerErrorResource')
        )
    ]
)]
class RegisterController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly JwtService  $jwtService
    ){}

    public function __invoke(RegisterRequest $request): SuccessAuthResource|BaseWithResponseResource|InternalServerErrorResource
    {
        try {
            if ($request->input('password') != $request->input('password_confirmation')){
                return new BaseWithResponseResource(null, "The passwords don't match", ResponseAlias::HTTP_BAD_REQUEST, 'failure');
            }

            $this->userService->create($request->validated());

            $token = $this->jwtService->guardApiAttempt($request->only('email', 'password'));

            return $this->jwtService->buildResponse($token, 'Registration was successful', true);
        }
        catch (Exception $e) {
            return new InternalServerErrorResource(['error' => $e->getMessage()]);
        }
    }
}
