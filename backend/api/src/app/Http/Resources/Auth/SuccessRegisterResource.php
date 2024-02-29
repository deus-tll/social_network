<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\BaseWithResponseResource;
use Illuminate\Contracts\Auth\Authenticatable;
use OpenApi\Attributes as OAT;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

#[OAT\Schema(
    schema: 'SuccessRegisterResource',
    properties: [
        new OAT\Property(
            property: 'status',
            type: 'string',
            example: 'success'
        ),
        new OAT\Property(
            property: 'message',
            type: 'string',
            example: 'Registration was successful'
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
class SuccessRegisterResource extends BaseWithResponseResource
{
    public function __construct(Authenticatable|null $user, string $token)
    {
        $data = [
            'user' => $user,
            'authorization' => new TokenResource($token)
        ];

        parent::__construct($data, 'Registration was successful', ResponseAlias::HTTP_CREATED);
    }
}
