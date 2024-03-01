<?php

namespace App\Services\Auth;

use App\Http\Resources\BaseWithResponseResource;
use App\Models\EmailVerificationToken;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EmailVerificationService
{
    /**
     * Generate verification link
     */
    public function generateVerificationLink(string $email): ?string
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $checkIfTokenExists = EmailVerificationToken::query()->where('email', $email)->first();
        $checkIfTokenExists?->delete();

        $token = Str::uuid();
        $url = config('app.url') . '/verify-email' . '?token=' . $token . '&email=' . $email;

        $saveToken = EmailVerificationToken::query()->create([
            'email' => $email,
            'token' => $token,
            'expired_at' => now()->addMinutes(10)
        ]);

        return $saveToken ? $url : null;
    }


    /**
     * Send verification link to a user
     */
    public function sendVerificationLink(object $user): void
    {
        $verificationUrl = $this->generateVerificationLink($user->email);
        Notification::send($user, new EmailVerificationNotification($verificationUrl));
    }


    /**
     * Verify email
     */
    public function verifyEmail(string $email, string $token): BaseWithResponseResource|JsonResponse
    {
        $user = User::query()->where('email', $email)->first();
        if (!$user) {
            return new BaseWithResponseResource(null, 'User not found', ResponseAlias::HTTP_NOT_FOUND, 'failure');
        }

        if ($user->hasVerifiedEmail()) {
            return new BaseWithResponseResource(null, 'Email has already been verified', ResponseAlias::HTTP_EXPECTATION_FAILED, 'failure');
        }

        $verifiedTokenResult = $this->verifyToken($email, $token);

        if ($verifiedTokenResult instanceof BaseWithResponseResource) {
            return $verifiedTokenResult;
        }

        if ($user->markEmailAsVerified()) {
            $verifiedTokenResult->delete();
            return new BaseWithResponseResource(null, 'Email has been verified successfully');
        }
        else {
            return new BaseWithResponseResource(null, 'Verification failed, please try again later', ResponseAlias::HTTP_EXPECTATION_FAILED, 'failure');
        }
    }


    /**
     * Verify token
     */
    public function verifyToken(string $email, string $token): BaseWithResponseResource|Builder
    {
        $foundToken = EmailVerificationToken::query()->where('email', $email)->where('token', $token)->first();

        if ($foundToken) {
            if ($foundToken->expired_at >= now()) {
                return $foundToken;
            }
            else {
                $foundToken->delete();
                return new BaseWithResponseResource(null, 'Token expired', ResponseAlias::HTTP_EXPECTATION_FAILED, 'failure');
            }
        }
        else {
            return new BaseWithResponseResource(null, 'Invalid token', ResponseAlias::HTTP_EXPECTATION_FAILED, 'failure');
        }
    }


    /**
     * Resend link with token
     */
    public function resendLink(string $email): BaseWithResponseResource
    {
        $user = User::query()->where('email', $email)->first();

        if ($user) {
            $this->sendVerificationLink($user);
            return new BaseWithResponseResource(null, 'Verification link sent successfully');
        }
        else {
            return new BaseWithResponseResource(null, 'User not found', ResponseAlias::HTTP_NOT_FOUND, 'failure');
        }
    }
}
