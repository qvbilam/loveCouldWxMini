<?php

namespace App\HttpController\Api\Video;

use App\HttpController\Api\ApiBase;
use App\Common\Model\Mysql\VideoPlate as VideoPlateModel;
use App\HttpController\Api\AuthBase;


class Plate extends AuthBase
{
    // 获取板块
    public function getNormalPlate()
    {
        $res = VideoPlateModel::getInstance()->getByConditon(['state' => 1, 'pid' => 0, 'delete_time' => 0], 'id as plate_id,name as plate_name,image', null, ['`order`', 'desc']);
        return $this->success($res);
    }

}