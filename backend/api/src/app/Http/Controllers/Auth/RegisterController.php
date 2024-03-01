<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\SuccessRegisterResource;
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
            content: new OAT\JsonContent(ref: '#/components/schemas/SuccessRegisterResource')
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
            response: 401,
            description: "Something went wrong while creating token for user",
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
                        example: "Something went wrong while creating token for user"
                    ),
                ]
            )
        ),
        new OAT\Response(
            response: 422,
            description: 'Validation errors occurred',
            content: new OAT\JsonContent(
                properties: [
                    new OAT\Property(
                        property: 'errors',
                        properties: [
                            new OAT\Property(
                                property: 'first_name',
                                type: 'array',
                                items: new OAT\Items(type: 'string', example: 'The first name field is required.')
                            ),
                            new OAT\Property(
                                property: 'last_name',
                                type: 'array',
                                items: new OAT\Items(type: 'string', example: 'The last name field is required.')
                            ),
                            new OAT\Property(
                                property: 'email',
                                type: 'array',
                                items: new OAT\Items(type: 'string', example: 'The email has already been taken.')
                            ),
                            new OAT\Property(
                                property: 'username',
                                type: 'array',
                                items: new OAT\Items(type: 'string', example: 'The username has already been taken.')
                            ),
                            new OAT\Property(
                                property: 'password',
                                type: 'array',
                                items: new OAT\Items(type: 'string', example: 'The password field is required.')
                            ),
                            new OAT\Property(
                                property: 'password_confirmation',
                                type: 'array',
                                items: new OAT\Items(type: 'string', example: 'The password confirmation field is required.')
                            )
                        ],
                        type: 'object'
                    )
                ],
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

    public function __invoke(RegisterRequest $request): SuccessRegisterResource|BaseWithResponseResource|InternalServerErrorResource
    {
        try {
            if ($request->input('password') != $request->input('password_confirmation')){
                return new BaseWithResponseResource(null, "The passwords don't match", ResponseAlias::HTTP_BAD_REQUEST, 'failure');
            }

            $this->userService->create($request->validated());

            $token = $this->jwtService->guardApiAttempt($request->only('email', 'password'));

            return $this->jwtService->buildResponseRegister($token);
        }
        catch (Exception $e) {
            return new InternalServerErrorResource(['error' => $e->getMessage()]);
        }
    }
}
