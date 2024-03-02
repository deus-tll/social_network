<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResendEmailVerificationLinkRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Http\Resources\BaseWithResponseResource;
use App\Services\Auth\EmailVerificationService;
use Illuminate\Http\JsonResponse;

class VerifyUserEmailController extends Controller
{
    public function __construct(private readonly EmailVerificationService $service){}

    public function verifyUserEmail(VerifyEmailRequest $request): BaseWithResponseResource|JsonResponse
    {
        $res = $this->service->verifyEmail($request->email, $request->token);

        return $res;
    }

    public function resendEmailVerificationLink(ResendEmailVerificationLinkRequest $request): BaseWithResponseResource
    {
        return $this->service->resendLink($request->email);
    }
}
