<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UploadUserAvatarRequest;
use App\Http\Resources\BaseWithResponseResource;
use App\Http\Resources\Errors\InternalServerErrorResource;
use App\Jobs\Profile\OptimizeAvatarJob;
use App\Services\Profile\AvatarService;
use Exception;
use OpenApi\Attributes as OAT;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

#[OAT\Post(
    path: '/api/profile/avatar',
    operationId: 'api.profile.avatar',
    summary: "Upload user's avatar",
    requestBody: new OAT\RequestBody(
        required: true,
        content: new OAT\JsonContent(ref: '#/components/schemas/UploadUserAvatarRequest')
    ),
    tags: ['profile'],
    responses: [
        new OAT\Response(
            response: 200,
            description: "Uploading of user's avatar went successfully",
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
                        example: "Avatar was uploaded successfully"
                    ),
                ]
            )
        ),
        new OAT\Response(
            response: 401,
            description: 'Unauthorized attempt to get access',
            content: new OAT\JsonContent(
                properties: [
                    new OAT\Property(
                        property: 'status',
                        type: 'string',
                        example: 'failure'
                    ),
                    new OAT\Property(
                        property: 'message',
                        type: 'string',
                        example: "Unauthorized"
                    ),
                ]
            )
        ),
        new OAT\Response(
            response: 422,
            description: 'Validation errors occurred',
            content: new OAT\JsonContent(
                properties: [
                    new OAT\Property(
                        property: 'errors',
                        properties: [
                            new OAT\Property(
                                property: 'avatar',
                                type: 'array',
                                items: new OAT\Items(type: 'string', example: 'The avatar field is required.')
                            )
                        ],
                        type: 'object'
                    )
                ],
            )
        ),
        new OAT\Response(
            response: 500,
            description: 'Some internal server error occurred',
            content: new OAT\JsonContent(ref: '#/components/schemas/InternalServerErrorResource')
        )]
)]
class UploadUserAvatarController extends Controller
{
    function __construct(){}

    public function __invoke(UploadUserAvatarRequest $request, AvatarService $avatarService): BaseWithResponseResource|InternalServerErrorResource
    {
        try {
            $userId = $request->user()->id;
            $avatar = $request->file('avatar');

            $avatarService->uploadAvatar($userId, $avatar);
            OptimizeAvatarJob::dispatch($userId)
                ->onQueue('avatars.jobs');

            return new BaseWithResponseResource(null, 'Avatar was uploaded successfully');
        }
        catch (Exception $e) {
            return new InternalServerErrorResource(['error' => $e->getMessage()]);
        }
    }
}
