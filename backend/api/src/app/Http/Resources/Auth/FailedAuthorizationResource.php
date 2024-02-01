<?php

namespace App\Http\Resources\Auth;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'FailedAuthorizationResource',
    properties: [
        new OAT\Property(
            property: 'status',
            type: 'string',
            example: 'false'
        ),
        new OAT\Property(
            property: 'message',
            type: 'string',
            example: 'Unauthorized'
        )
    ]
)]
class FailedAuthorizationResource extends JsonResource
{
    public function __construct()
    {
        $data = [
            'status' => 'false',
            'message' => 'Unauthorized'
        ];

        parent::__construct($data);
    }

    public function withResponse($request, $response): void
    {
        $response->setStatusCode(ResponseAlias::HTTP_UNAUTHORIZED);
    }
}
