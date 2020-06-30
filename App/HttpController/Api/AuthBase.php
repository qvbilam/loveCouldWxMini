<?php

// 需要登录的控制器继承

namespace App\HttpController\Api;

use App\Common\Lib\Sign;
use App\Common\Model\Mysql\User as UserModel;

abstract class AuthBase extends ApiBase
{
    protected $isLogin = true; // 是否需要验证
    public $userId;
    public $userType = 0; // 用户类型

    protected function onRequest(?string $action): ?bool
    {
        parent::onRequest($action);
        // 怕断是否需要登录

        // 获取token
        $token = $this->request()->getHeader('token');
        if (!$token) {
            $this->error('请先登录', 403);
            return false;
        }
        // 验证token
        $userTokenInfo = Sign::decodeJwt($token[0]);
        $this->userId = $userTokenInfo;
        $userInfo = UserModel::getInstance()->getByConditon(['id' => $this->userId, 'delete_time' => 0], 'state,type', 1);
        if ($userInfo['state'] < 1) {
            $this->error('禁止登录:' . json_encode($userInfo) , -1);
            return false;
        }
        $this->userType = $userInfo['type'];
        return true;
    }
}