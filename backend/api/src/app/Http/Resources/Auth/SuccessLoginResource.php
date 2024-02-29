<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\BaseWithResponseResource;
use Illuminate\Contracts\Auth\Authenticatable;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'SuccessLoginResource',
    properties: [
        new OAT\Property(
            property: 'status',
            type: 'string',
            example: 'success'
        ),
        new OAT\Property(
            property: 'message',
            type: 'string',
            example: 'User logged in successfully'
        ),
        new OAT\Property(
            property: 'user',
            ref: '#/components/schemas/User',
            type: 'object'
        ),
        new OAT\Property(
            property: 'avatars',
            description: "Array of user's avatars. Original version of avatar can be null and also of various extension",
            example: [
                'http://localhost/storage/avatars/1/large.webp',
                'http://localhost/storage/avatars/1/medium.webp',
                'http://localhost/storage/avatars/1/small.webp',
                'http://localhost/storage/avatars/1/original.jpg'
            ]
        ),
        new OAT\Property(
            property: 'authorization',
            ref: '#/components/schemas/TokenResource',
            type: 'object'
        ),
    ]
)]
class SuccessLoginResource extends BaseWithResponseResource
{
    public function __construct(Authenticatable|null $user, string $token, array $avatars)
    {
        $data = [
            'user' => $user,
            'avatars' => $avatars,
            'authorization' => new TokenResource($token)
        ];

        parent::__construct($data, 'User logged in successfully');
    }
}
