<?php

namespace App\Http\Requests\Api;

class LoginRequest extends ApiRequest
{
    public function rules(): array
    {
        return ['login' => ['required', 'string'], 'password' => ['required', 'string', 'min:8']];
    }
}
