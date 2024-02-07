<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\SuccessAuthResource;
use App\Http\Resources\Errors\InternalServerErrorResource;
use App\Services\Auth\JwtService;
use OpenApi\Attributes as OAT;

#[OAT\Get(
    path: '/api/auth/refresh-token',
    operationId: 'api.auth.refreshToken',
    summary: 'Refresh user token',
    requestBody: new OAT\RequestBody(
        required: false
    ),
    tags: ['auth'],
    responses: [
        new OAT\Response(
            response: 200,
            description: 'Token successfully refreshed',
            content: new OAT\JsonContent(ref: '#/components/schemas/SuccessAuthResource')
        ),
        new OAT\Response(
            response: 500,
            description: 'Some internal server error occurred',
            content: new OAT\JsonContent(ref: '#/components/schemas/InternalServerErrorResource')
        )]
)]
class RefreshTokenController extends Controller
{
    public function __construct(
        private readonly JwtService  $jwtService,
    ){}

    public function __invoke(): SuccessAuthResource|InternalServerErrorResource
    {
        try {
            $token = $this->jwtService->guardApiRefresh();
            return $this->jwtService->buildResponse($token, 'Token successfully refreshed');
        }
        catch (\Exception $e) {
            return new InternalServerErrorResource(['error' => $e->getMessage()]);
        }
    }
}
