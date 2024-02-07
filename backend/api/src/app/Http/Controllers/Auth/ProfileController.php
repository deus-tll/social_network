<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseWithResponseResource;
use App\Http\Resources\Errors\InternalServerErrorResource;
use App\Services\Auth\JwtService;
use OpenApi\Attributes as OAT;

#[OAT\Get(
    path: '/api/auth/profile',
    operationId: 'api.auth.profile',
    summary: 'Get a user profile',
    requestBody: new OAT\RequestBody(
        required: false
    ),
    tags: ['auth'],
    responses: [
        new OAT\Response(
            response: 200,
            description: 'Profile data extracted successfully',
            content: new OAT\JsonContent(
                properties: [
                    new OAT\Property(
                        property: 'status',
                        type: 'string',
                        example: 'success'
                    ),
                    new OAT\Property(
                        property: 'message',
                        type: 'string',
                        example: 'Profile data extracted successfully'
                    ),
                    new OAT\Property(
                        property: 'user',
                        ref: '#/components/schemas/User',
                        type: 'object'
                    )
                ]
            )
        ),
        new OAT\Response(
            response: 500,
            description: 'Some internal server error occurred',
            content: new OAT\JsonContent(ref: '#/components/schemas/InternalServerErrorResource')
        )]
)]
class ProfileController extends Controller
{
    public function __construct(
        private readonly JwtService  $jwtService,
    ){}

    public function __invoke(): BaseWithResponseResource|InternalServerErrorResource
    {
        try {
            $user = $this->jwtService->getUser();
            return new BaseWithResponseResource(['user' => $user], 'Profile data extracted successfully');
        }
        catch (\Exception $e) {
            return new InternalServerErrorResource(['error' => $e->getMessage()]);
        }
    }
}
