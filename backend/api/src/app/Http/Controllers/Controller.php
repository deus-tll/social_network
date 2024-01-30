<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OAT;

#[
    OAT\Info(
        version: '1.0.0',
        description: "## An attempt to implement a social network with basic functionality using \"Laravel 10\" for backend and \"React Framework\" for frontend.",
        title: 'Social Network',
    ),
    OAT\Server(url: 'http://localhost', description: 'Local API server'),
    OAT\SecurityScheme(
        securityScheme: 'BearerToken',
        type: 'http',
        bearerFormat: 'JWT',
        scheme: 'bearer'
    ),
    OAT\Schema(
        schema: 'ValidationError',
        properties: [
            new OAT\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
            new OAT\Property(
                property: 'errors',
                properties: [
                    new OAT\Property(
                        property: 'key 1',
                        type: 'array',
                        items: new OAT\Items(type: 'string', example: 'Error message 1')
                    ),
                    new OAT\Property(
                        property: 'key 2',
                        type: 'array',
                        items: new OAT\Items(type: 'string', example: 'Error message 2')
                    ),
                ],
                type: 'object'
            ),

        ]
    )
]
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
