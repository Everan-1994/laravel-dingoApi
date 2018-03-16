<?php

namespace App\Http\Controllers\Api;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response as Psr7Response;
use League\OAuth2\Server\AuthorizationServer;
use App\Http\Requests\Api\AuthorizationRequest;
use League\OAuth2\Server\Exception\OAuthServerException;

class AuthorizationsController extends Controller
{
//    public function store(AuthorizationRequest $request)
//    {
//        $username = $request->phone;
//
//        filter_var($username, FILTER_VALIDATE_EMAIL) ?
//            $credentials['email'] = $username :
//            $credentials['phone'] = $username;
//
//        $credentials['password'] = $request->password;
//
//        if (!$token = auth()->guard('api')->attempt($credentials)) {
//            return $this->response->errorUnauthorized('用户名或密码错误');
//        }
//
//        return $this->respondWithToken($token)->setStatusCode(201);
//
//    }

    public function store(AuthorizationRequest $originRequest, AuthorizationServer $server, ServerRequestInterface $serverRequest)
    {
        try {
            return $server->respondToAccessTokenRequest($serverRequest, new Psr7Response)->withStatus(201);
        } catch (OAuthServerException $e) {
            return $this->response->errorUnauthorized($e->getMessage());
        }
    }

    public function update(AuthorizationServer $server, ServerRequestInterface $serverRequest)
    {
        try {
            return $server->respondToAccessTokenRequest($serverRequest, new Psr7Response);
        } catch(OAuthServerException $e) {
            return $this->response->errorUnauthorized($e->getMessage());
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->guard('api')->factory()->getTTL() * 60
        ]);
    }

//    public function update()
//    {
//        $token = auth()->guard('api')->refresh();
//        return $this->respondWithToken($token);
//    }

//    public function destroy()
//    {
//        auth()->guard('api')->logout();
//        return $this->response->noContent();
//    }

    public function destroy()
    {
        $this->user()->token()->revoke();
        return $this->response->noContent();
    }
}
