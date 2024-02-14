<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\BaseWithResponseResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'SuccessAuthResource',
    properties: [
        new OAT\Property(
            property: 'status',
            type: 'string',
            example: 'success'
        ),
        new OAT\Property(
            property: 'message',
            type: 'string',
            example: '"Registration was successful" or "User logged in successfully"'
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
class SuccessAuthResource extends BaseWithResponseResource
{
    protected bool $isRegistration;

    public function __construct(Authenticatable|null $user, string $token, string $message, bool $isRegistration)
    {
        $this->isRegistration = $isRegistration;

        $data = [
            'user' => $user,
            'authorization' => new TokenResource($token)
        ];

        $statusCode = $this->isRegistration ? ResponseAlias::HTTP_CREATED : ResponseAlias::HTTP_OK;

        parent::__construct($data, $message, $statusCode);
    }
}
