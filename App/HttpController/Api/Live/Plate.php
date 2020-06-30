<?php

namespace App\HttpController\Api\Live;

use App\HttpController\Api\ApiBase;
use App\Common\Model\Mysql\LivePlate as LivePlateModel;


class Plate extends ApiBase
{
    // 获取板块
    public function index()
    {
        $res = LivePlateModel::getInstance()->getByConditon(['state' => 1, 'pid' => 0], 'id as plate_id,name as plate_name', null, ['`order`', 'desc']);
        foreach ($res as &$v) {
            $v['data'] = LivePlateModel::getInstance()->getByConditon(['pid' => $v['plate_id']], 'id as plate_id,name as plate_name', 4, ['`order`', 'desc']);
        }
        return $this->success($res);
    }

    // 获取子板块
    public function getSonPlate()
    {
        if (!$this->params['plate_id']) {
            return $this->error('参数错误');
        }
        // 查询传入板块信息
        $plateInfo = LivePlateModel::getInstance()->getByConditon(['id' => $this->params['plate_id']], 'id,state', 1);
        if (empty($plateInfo) || $plateInfo['state'] != 1) {
            return $this->error('当前板块不存在');
        }
        // 获取父板块下的数据
        $plateData = LivePlateModel::getInstance()->getByConditon(['pid' => $this->params['plate_id'], 'state' => 1], 'id as plate_id,name as plate_name');
        return $this->success($plateData);
    }

}