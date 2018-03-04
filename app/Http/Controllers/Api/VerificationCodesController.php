<?php

namespace App\Http\Controllers\Api;

use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\VerificationCodeRequest;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $phone = $request->phone;

        // 生成4位随机数字，左侧补0
        $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

        // 本地或者测试环境，不必每次都真实发送验证码
        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            try {
                $easySms->send($phone, [
                    'template' => 'SMS_126462014',
                    'data' => [
                        'code' => $code
                    ],
                ]);
            } catch (\GuzzleHttp\Exception\ClientException $exception) {
                $response = $exception->getResponse();
                $result = json_decode($response->getBody()->getContents(), true);
                return $this->response->errorInternal($result['msg'] ?: '短信发送异常');
            }
        }

        $key = 'verificationCode_' . str_random(15);
        $expiredAt = now()->addMinutes(5);

        cache()->put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return $this->response->array([
            'key' => $key,
            'expiredAt' => $expiredAt->toDateTimeString()
        ])->setStatusCode(201);
    }
}
