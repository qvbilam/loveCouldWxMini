<?php

namespace App\HttpController\Api\Video;

use App\HttpController\Api\ApiBase;
use App\Common\Model\Mysql\VideoDetail as VideoDetailModel;


class VideoDetail extends ApiBase
{
    public function getNormalVideo()
    {
        $where = ['delete_time' => 0];
        // 视频详情id
        if (!empty($this->params['id'])) {
            $where['id'] = $this->params['id'];
        }
        $fields = "id,video_id,name,num,content,image,src";
        $res = VideoDetailModel::getInstance()->getByConditon($where, $fields, 1);
        return $this->success($res);
    }
}