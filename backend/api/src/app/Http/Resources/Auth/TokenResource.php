<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'TokenResource',
    properties: [
        new OAT\Property(
            property: 'access_token',
            type: 'string',
            example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2FwaS9hdXRoL3JlZ2lzdGVyIiwiaWF0IjoxNzA2NTUyMTM4LCJleHAiOjE3MDY5MTIxMzgsIm5iZiI6MTcwNjU1MjEzOCwianRpIjoiRk9hclkyZ2VvckhPT1llbSIsInN1YiI6IjQiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwiZW1haWwiOiJ2YXN5YTJAdmFzeWEuY29tIiwibmFtZSI6InZhc3lhMiJ9.APeVTaT7gnkVVboKkfXDndq1vjQFiiaMPCw21aS9hL8'
        ),
        new OAT\Property(
            property: 'type',
            type: 'string',
            example: 'bearer'
        ),
    ]
)]
class TokenResource extends JsonResource
{
    public function __construct(string $token)
    {
        $data = [
            'access_token' => $token,
            'type' => 'bearer'
        ];

        parent::__construct($data);
    }
}
