<?php

namespace App\Common\Lib;

use EasySwoole\Jwt\Jwt;

class Sign
{
    /*
     * 用户密码签名算法
     * */
    static public function pwdSign($pwd, $key)
    {
        $data = ['pwd' => $pwd];
        $data = ksort($data);
        $md5 = md5(urldecode($data) . '&key=' . $key);
        $sign = strtoupper($md5);
        return $sign;
    }

    static public function encodeJwt($userId)
    {
        $jwtObject = Jwt::getInstance()
            ->setSecretKey('QvBiLam_live')// 秘钥
            ->publish();
        $jwtObject->setAlg('HMACSHA256'); // 加密方式
        $jwtObject->setAud($userId); // 用户
        $jwtObject->setExp(time() + 6 * 60 * 3600); // 过期时间
        $jwtObject->setIat(time()); // 发布时间
        $jwtObject->setIss('QvBiLam'); // 发行人
        $jwtObject->setJti(md5(time())); // jwt id 用于标识该jwt
        $jwtObject->setNbf(time() + 60 * 5); // 在此之前不可用
        // 最终生成的token
        $token = $jwtObject->__toString();
        return $token;
    }

    static public function decodeJwt($token)
    {
        try {
            // 如果encode设置了秘钥,decode 的时候要指定
            $jwtObject = Jwt::getInstance()->setSecretKey('QvBiLam_live')->decode($token);
            $status = $jwtObject->getStatus();
            switch ($status) {
                case  1:
                    echo '验证通过';
                    $userId = $jwtObject->getAud();
                    return $userId;
                    break;
                case  -1:
                    throw new \Exception('无效token');
                    break;
                case  -2:
                    throw new \Exception('token过期');
            }
        } catch (\EasySwoole\Jwt\Exception $e) {
            throw new \Exception('token验证错误');
        }
    }
}