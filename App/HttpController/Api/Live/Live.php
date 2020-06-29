<?php

namespace App\HttpController\Api\Live;

use App\Common\Model\Mysql\LiveRoom as LiveRoomModel;
use App\Common\Model\Mysql\User as UserModel;
use App\HttpController\Api\ApiBase;

// 直播控制器
class Live extends ApiBase
{
    // 获取直播数据
    public function getData()
    {
        if (empty($this->params['live_id'])) {
            return $this->error('参数错误');
        }
        // 获取间信息
        $liveData = LiveRoomModel::getInstance()->getByConditon(['id' => $this->params['live_id']], 'user_id,live_number,live_image,live_notice,state', 1);
        if (empty($liveData) || $liveData['state'] < 0) {
            return $this->error('直播间不存在');
        }
        if ($liveData['state'] == 0) {
            return $this->error('未开播');
        }
        return $this->success($liveData);
    }
}