<?php
/**
 * Created by PhpStorm.
 * User: qvbilam
 * Date: 2020-05-07
 * Time: 15:00
 */

namespace App\HttpController\Api;

use App\HttpController\Api\Validate\User as UserValidate;
use App\Common\Model\Mysql\User as UserModel;
use App\Common\Lib\Sign;
use App\Common\Lib\Random;


class Login extends ApiBase
{
    // 登录
    public function login()
    {
        // 验证参数
        $res = (new UserValidate())->login($this->params);
        $userInfo = json_decode($res,true);
        // 绑定用户与连接数
        return $this->success([
            'user_id' => $userInfo['id'],
            'nick_name' => $userInfo['nick_name'],
            'vip' => $userInfo['vip'],
            'token' => Sign::encodeJwt($userInfo['id']),
        ]);
    }

    // 注册
    public function register()
    {
        // 验证参数
        (new UserValidate())->register($this->params);
        // 添加用户
        $data = [
            'phone' => $this->params['phone'],
            'nick_name' => $this->params['nick_name'],
            'user_name' => $this->params['user_name'],
            'salt' => Random::getRandomString(5),
            'create_time' => time(),
            'update_time' => time(),
        ];
        $data['password'] = Sign::pwdSign($this->params['password'], $data['salt']);
        $res = UserModel::getInstance()->insert($data);
        if (!$res) {
            return $this->error('添加失败');
        }
        return $this->success([
            'user_id' => $res,
            'nick_name' => $data['nick_name'],
            'vip' => 0,
            'token' => Sign::encodeJwt($res)
        ]);
    }
}