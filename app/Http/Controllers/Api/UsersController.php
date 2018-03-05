<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Requests\Api\UserRequest;
use App\Transformers\UserTransformer;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $verifyData = cache()->get($request->verification_key);

        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }

        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回401
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name'     => $request->name,
            'phone'    => $verifyData['phone'],
            'password' => bcrypt($request->password),
        ]);

        // 清除验证码缓存
        cache()->forget($request->verification_key);

        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'access_token' => auth()->guard('api')->fromUser($user),
                'token_type'   => 'Bearer',
                'expires_in'   => auth()->guard('api')->factory()->getTTL() * 60
            ])->setStatusCode(201);
    }

    public function me()
    {
        // $this->user() 等同于 auth()->guard('api')->user(); 获取token对应的用户
        return $this->response->item($this->user(), new UserTransformer());
    }

}
