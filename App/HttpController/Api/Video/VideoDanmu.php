<?php

namespace App\HttpController\Api\Video;

use App\HttpController\Api\ApiBase;

use App\Common\Model\Mysql\VideoDanmu as VideoDanmuModel;
use App\HttpController\Api\AuthBase;

class VideoDanmu extends AuthBase
{
    // 通过视频id获取弹幕
    public function getNormalDanmuByVideoId()
    {
        if (empty($this->params['video_id'])) {
            return $this->error('参数错误');
        }
        $res = VideoDanmuModel::getInstance()->getPaginationByConditon([
            'vid' => $this->params['video_id'],
            'display' => 1,
        ], 'uid,text,color,size,position,time,type', $this->params['size'], $this->params['page'], ['time', 'ASC']);
        return $this->success($res);
    }
}