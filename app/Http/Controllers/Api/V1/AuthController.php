<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\Api\ModelResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $user = User::where('username', $credentials['login'])->orWhere('mobile', $credentials['login'])->first();
        if (! $user || ! $user->is_active || ! Hash::check($credentials['password'], $user->password)) {
            return $this->failure('اطلاعات ورود نادرست است.', null, 401);
        }
        $user->update(['last_login_at' => now(), 'last_login_ip' => $request->ip()]);
        $token = $user->createToken('api-v1')->plainTextToken;
        return $this->success(['token' => $token, 'user' => (new ModelResource($user))->resolve()], 'ورود با موفقیت انجام شد.');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();
        return $this->success(null, 'از حساب کاربری خارج شدید.');
    }

    public function me(Request $request)
    {
        return $this->success(new ModelResource($request->user()), 'اطلاعات کاربر دریافت شد.');
    }

    public function refreshToken(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();
        return $this->success(['token' => $request->user()->createToken('api-v1')->plainTextToken], 'توکن جدید صادر شد.');
    }
}
