<?php
/**
 * Created by PhpStorm.
 * User: qvbilam
 * Date: 2020-06-09
 * Time: 13:13
 */

namespace App\HttpController\Api\Validate;

use EasySwoole\Validate\Validate;
use App\Common\Lib\RedisKey;
use App\Common\Model\Redis\RedisBase;
use App\Common\Model\Mysql\User as UserModel;
use App\Common\Lib\Sign;

class User
{
    // 验证登录请求参数
    public function login($data)
    {
        $validate = new Validate();
        $validate->addColumn('user_name')->required('用户名不能为空');
        $validate->addColumn('password')->required('密码不能为空');
        $bool = $validate->validate($data);
        if (!$bool) {
            throw new \Exception($validate->getError()->__toString());
        }
        $userInfo = UserModel::getInstance()->getByConditon(['user_name' => $data['user_name']], 'id,vip,state,nick_name,salt,password', 1);
        if (!$userInfo) {
            throw new  \Exception('账号不存在');
        }
        if ($userInfo['state'] < 0) {
            throw new  \Exception('账号禁止登录');
        }
        if ($userInfo['password'] != Sign::pwdSign($data['password'], $userInfo['salt'])) {
            throw new  \Exception('密码错误');
        }
        return json_encode([
            'id' => $userInfo['id'],
            'nick_name' => $userInfo['nick_name'],
            'vip' => $userInfo['vip']]);
    }

    // 验证注册
    public function register($data)
    {
        $validate = new Validate();
        $validate->addColumn('phone')->required('手机号不能为空')->length(11, '最小长度不小于11位');
        $validate->addColumn('code')->required('验证码不能为空');
        $validate->addColumn('nick_name')->required('昵称不能为空')->lengthMax(32, '昵称超长');
        $validate->addColumn('user_name')->required('用户名不能为空')->lengthMax(32, '用户名超长');
        $validate->addColumn('password')->required('密码不能为空')->lengthMax(32, '密码超长');
        $bool = $validate->validate($data);
        if (!$bool) {
            throw new \Exception($validate->getError()->__toString());
        }
        // 获取redis-code
        $redis = RedisBase::getInstance()->redis;
        $code = $redis->get(RedisKey::smsKey($data['phone']));
        if ($code != $data['code']) {
            throw new  \Exception('验证码错误');
        }
        // 验证手机号是否正确
        $checkPhone = UserModel::getInstance()->getByConditon(['phone' => $data['phone']], 'id', 1);
        if ($checkPhone) {
            throw new  \Exception('手机号已存在');
        }
        // 验证用户名是否存在
        $chekUsername = UserModel::getInstance()->getByConditon(['user_name' => $data['user_name']], 'id', 1);
        if ($chekUsername) {
            throw new \Exception('用户名存在');
        }
        return true;
    }
}