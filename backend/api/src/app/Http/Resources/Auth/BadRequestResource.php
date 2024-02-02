<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'BadRequestResource',
    properties: [
        new OAT\Property(
            property: 'status',
            type: 'string',
            example: 'false'
        ),
        new OAT\Property(
            property: 'message',
            type: 'string',
            example: "Invalid data, The passwords don't match, etc."
        )
    ]
)]
class BadRequestResource extends JsonResource
{
    public function __construct(string $message)
    {
        $data = [
            'status' => 'false',
            'message' => $message
        ];

        parent::__construct($data);
    }

    public function withResponse($request, $response): void
    {
        $response->setStatusCode(ResponseAlias::HTTP_BAD_REQUEST);
    }
}
