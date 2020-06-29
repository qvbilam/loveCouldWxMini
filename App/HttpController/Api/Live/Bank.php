<?php

namespace App\HttpController\Api\Live;

use App\HttpController\Api\ApiBase;
use App\Common\Model\Mysql\LiveBank as LiveBankModel;


class Bank extends ApiBase
{
    // 获取板块
    public function index()
    {
        $res = LiveBankModel::getInstance()->getByConditon(['state' => 1, 'pid' => 0], 'id as bank_id,name as bank_name', null, ['`order`', 'desc']);
        foreach ($res as &$v) {
            $v['data'] = LiveBankModel::getInstance()->getByConditon(['pid' => $v['bank_id']], 'id as bank_id,name as bank_name', 4, ['`order`', 'desc']);
        }
        return $this->success($res);
    }

    // 获取子板块
    public function getSonBank()
    {
        if (!$this->params['bank_id']) {
            return $this->error('参数错误');
        }
        // 查询传入板块信息
        $bankInfo = LiveBankModel::getInstance()->getByConditon(['id' => $this->params['bank_id']], 'id,state', 1);
        if (empty($bankInfo) || $bankInfo['state'] != 1) {
            return $this->error('当前板块不存在');
        }
        // 获取父板块下的数据
        $bankData = LiveBankModel::getInstance()->getByConditon(['pid' => $this->params['bank_id'], 'state' => 1], 'id as bank_id,name as bank_name');
        return $this->success($bankData);
    }

}