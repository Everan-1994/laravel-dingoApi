<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

class WeChatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        Log::info('request arrived.');

        $app = app('wechat.official_account');
        $app->server->push(function($message){
            return "欢迎关注 Everan！" . '你发了一句：' . $message;
        });

        return $app->server->serve();
    }

    public function index()
    {
        $app = app('wechat.official_account');
        $response = $app->oauth->scopes(['snsapi_userinfo'])
            ->redirect('http://laravel-api.wei/user');
        return $response;
    }

    public function user()
    {
        $app = app('wechat.official_account');
        $response = $app->oauth->user()->toArray();
        dd($response);
    }
}
