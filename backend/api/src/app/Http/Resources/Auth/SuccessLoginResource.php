<?php

namespace App\Http\Resources\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'SuccessLoginResource',
    properties: [
        new OAT\Property(
            property: 'status',
            type: 'string',
            example: 'true'
        ),
        new OAT\Property(
            property: 'message',
            type: 'string',
            example: 'User created successfully'.' | '.'User logged in successfully'
        ),
        new OAT\Property(
            property: 'user',
            ref: '#/components/schemas/User',
            type: 'object'
        ),
        new OAT\Property(
            property: 'authorization',
            ref: '#/components/schemas/TokenResource',
            type: 'object'
        ),
    ]
)]
class SuccessLoginResource extends JsonResource
{
    protected bool $isRegistration;

    public function __construct(Authenticatable|null $user, string $token, string $message, bool $isRegistration)
    {
        $this->isRegistration = $isRegistration;

        $data = [
            'status' => 'true',
            'message' => $message,
            'user' => $user,
            'authorization' => new TokenResource($token)
        ];
        parent::__construct($data);
    }

    public function withResponse($request, $response): void
    {
        $statusCode = $this->isRegistration ? ResponseAlias::HTTP_CREATED : ResponseAlias::HTTP_OK;
        $response->setStatusCode($statusCode);
    }
}
