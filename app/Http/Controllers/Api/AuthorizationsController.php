<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationRequest;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request)
    {
        $username = $request->phone;

        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;

        $credentials['password'] = $request->password;

        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return $this->response->errorUnauthorized('用户名或密码错误');
        }

        return $this->respondWithToken($token)->setStatusCode(201);

    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->guard('api')->factory()->getTTL() * 60
        ]);
    }

    public function update()
    {
        $token = auth()->guard('api')->refresh();
        return $this->respondWithToken($token);
    }

    public function destroy()
    {
        auth()->guard('api')->logout();
        return $this->response->noContent();
    }
}
