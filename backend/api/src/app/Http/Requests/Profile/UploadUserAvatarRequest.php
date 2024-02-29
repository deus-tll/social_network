<?php

namespace App\Http\Requests\Profile;

use App\Http\Traits\ValidationTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'UploadUserAvatarRequest',
    required: ['avatar'],
    properties: [
        new OAT\Property(
            property: 'avatar',
            description: 'The avatar image file to upload.',
            type: 'string',
            format: 'binary'
        )
    ]
)]
class UploadUserAvatarRequest extends FormRequest
{
    use ValidationTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'avatar' => ['required', 'image', 'max:8096'],
        ];
    }
}
