<?php

namespace App\HttpController\Api;

use App\Common\Lib\SendSms;
use App\Common\Model\Mysql\User;
use App\Common\Model\Redis\RedisBase;
use App\Common\Lib\RedisKey;

class Sms extends ApiBase
{
    /*
     * 修改密码 手机验证码
     * */
    public function register()
    {
        if (empty($this->params['phone'])) {
            return $this->error('手机号错误');
        }
        $checkPhone = User::getInstance()->getValue(['phone' => $this->params['phone']], 'id', 1);
        if ($checkPhone) {
            return $this->error('手机号已被注册');
        }
        mt_srand();
        $code = mt_rand(1000, 9999);
        $res = SendSms::sendPhoneSms($this->params['phone'], $code);
        $res = json_decode($res, true);
        if ($res['code'] != '000000') {
            return $this->error($res['msg']);
        }
        $phone = $this->params['phone'];
        go(function () use ($phone, $code) {
            $key = RedisKey::smsKey($phone);
            // 设置redis 3分钟过期
            RedisBase::getInstance()->redis->setEx($key, 3 * 60, $code);
        });
        return $this->success();
    }


}