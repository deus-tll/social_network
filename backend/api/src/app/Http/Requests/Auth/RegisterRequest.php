<?php

namespace App\Http\Requests\Auth;

use App\Http\Traits\ValidationTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'RegisterRequest',
    required: ['first_name', 'last_name', 'email', 'password', 'password_confirmation'],
    properties: [
        new OAT\Property(
            property: 'first_name',
            type: 'string',
            example: 'John'
        ),
        new OAT\Property(
            property: 'last_name',
            type: 'string',
            example: 'Doe'
        ),
        new OAT\Property(
            property: 'email',
            type: 'string',
            format: 'email',
            example: 'john@example.com'
        ),
        new OAT\Property(
            property: 'username',
            type: 'string',
            example: 'john_doe123'
        ),
        new OAT\Property(
            property: 'password',
            type: 'string',
            example: '123456'
        ),
        new OAT\Property(
            property: 'password_confirmation',
            type: 'string',
            example: '123456'
        ),
    ]
)]
class RegisterRequest extends FormRequest
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
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', Rule::unique('users', 'email')->ignore(null),],
            'username' => ['required', 'string', Rule::unique('users', 'username')->ignore(null),],
            'password' => ['required', 'string'],
            'password_confirmation' => ['required', 'string']
        ];
    }
}
